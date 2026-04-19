import { defineStore } from 'pinia';
import axios from 'axios';

export const useCharacterStore = defineStore('character', {
    state: () => ({
        id: null,
        name: '',
        level: 1,
        experience: 0,
        gold: 0,
        hp: 100,
        max_hp: 100,
        mana: 50,
        max_mana: 50,
        attack: 10,
        defense: 10,
        speed: 10,
        luck: 5,
        strength: 10,
        dexterity: 10,
        intelligence: 10,
        vitality: 10,
        inventory: [],
        current_location_id: 1,
        currentLocation: null,
        isDead: false,
        monster: null,
        logs: [],
        presentPlayers: [],
        // Internal — not persisted
        _echoChannel: null,
        _locationChannel: null,
    }),

    actions: {
        async fetchActiveCharacter() {
            try {
                const response = await axios.get('/api/active-character');
                if (response.data.status === 'success') {
                    this.$patch(response.data.data);
                    this.isDead = this.hp <= 0;
                }
            } catch (error) {
                console.error('Error fetching character:', error);
            }
        },

        /**
         * HTTP polling heartbeat — drives the server-side combat engine.
         * The response is the source of truth; CombatTickEvent via WebSocket
         * delivers the same data with lower latency when Reverb is running.
         * Keep alive during Phase 2.1 as a safety net (removed in Phase 2.2).
         */
        async heartbeatTick() {
            try {
                const response = await axios.post('/api/character/heartbeat');
                if (response.data.status === 'success') {
                    const data = response.data.data;
                    this.$patch(data.character);
                    this.monster = data.monster;

                    if (data.logs && data.logs.length > 0) {
                        this.logs.push(...data.logs);
                        if (this.logs.length > 50) {
                            this.logs = this.logs.slice(this.logs.length - 50);
                        }
                    }

                    this.isDead = this.hp <= 0;
                }
            } catch (error) {
                console.error('Error during heartbeat:', error);
            }
        },

        /**
         * Subscribe to the user's private channel and listen for CombatTickEvents.
         * Called once after the active character is loaded in DashboardView.
         *
         * @param {number} userId - The authenticated user's ID.
         */
        initWebSocket(userId) {
            if (!window.Echo) {
                console.warn('[Echo] window.Echo not available — WebSocket disabled.');
                return;
            }

            this._userId = userId;

            this._echoChannel = window.Echo
                .private(`App.Models.User.${userId}`)
                .listen('.CombatTickEvent', (e) => {
                    this.$patch(e.character);
                    this.monster = e.monster ?? null;

                    if (e.logs && e.logs.length > 0) {
                        this.logs.push(...e.logs);
                        if (this.logs.length > 50) {
                            this.logs = this.logs.slice(-50);
                        }
                    }

                    this.isDead = this.hp <= 0;
                });

            console.info(`[Echo] Subscribed to private channel: App.Models.User.${userId}`);
        },

        /**
         * Unsubscribe from ALL Echo channels and clean up listeners.
         * Must be called on logout and component unmount to prevent ghost listeners.
         */
        destroyWebSocket() {
            if (!window.Echo) {
                return;
            }

            if (this._userId) {
                window.Echo.leave(`App.Models.User.${this._userId}`);
                console.info(`[Echo] Left private channel: App.Models.User.${this._userId}`);
            }

            this._echoChannel = null;
            this._userId      = null;

            this.leaveLocationChannel();
        },

        async changeLocation(locationId) {
            try {
                const response = await axios.post('/api/character/location', { location_id: locationId });
                if (response.data.status === 'success') {
                    const data = response.data.data;
                    this.current_location_id = data.character.current_location_id;
                    this.currentLocation = data.character.current_location;
                    this.monster = null; // Clear monster when changing zones
                    
                    // Re-join location channel
                    this.joinLocationChannel(this.current_location_id);
                }
            } catch (error) {
                console.error('Error changing location:', error);
            }
        },

        joinLocationChannel(locationId) {
            if (!window.Echo) return;

            this.leaveLocationChannel();

            this.presentPlayers = [];

            this._locationChannel = window.Echo.join(`location.${locationId}`)
                .here((users) => {
                    this.presentPlayers = users;
                })
                .joining((user) => {
                    this.presentPlayers.push(user);
                })
                .leaving((user) => {
                    this.presentPlayers = this.presentPlayers.filter(u => u.id !== user.id);
                });

            console.info(`[Echo] Joined presence channel: location.${locationId}`);
        },

        leaveLocationChannel() {
            if (!window.Echo || !this._locationChannel) return;

            const channelName = this._locationChannel.name;
            window.Echo.leave(channelName);
            this._locationChannel = null;
            this.presentPlayers = [];
            console.info(`[Echo] Left presence channel: ${channelName}`);
        },

        async fetchInventory() {
            try {
                const response = await axios.get('/api/inventory');
                if (response.data.status === 'success') {
                    this.inventory = response.data.data;
                }
            } catch (error) {
                console.error('Error fetching inventory:', error);
            }
        },

        async equipItem(characterItemId) {
            try {
                const response = await axios.post('/api/inventory/equip', { character_item_id: characterItemId });
                if (response.data.status === 'success') {
                    this.$patch(response.data.data.character);
                    await this.fetchInventory();
                }
            } catch (error) {
                console.error('Error equipping item:', error);
            }
        },

        async unequipItem(characterItemId) {
            try {
                const response = await axios.post('/api/inventory/unequip', { character_item_id: characterItemId });
                if (response.data.status === 'success') {
                    this.$patch(response.data.data.character);
                    await this.fetchInventory();
                }
            } catch (error) {
                console.error('Error unequipping item:', error);
            }
        },
    },

    persist: {
        // Exclude internal runtime and large data fields from localStorage
        omit: ['_echoChannel', '_locationChannel', '_userId', 'logs', 'presentPlayers', 'inventory'],
    },
});
