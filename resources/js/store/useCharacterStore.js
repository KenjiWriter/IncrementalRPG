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
        isDead: false,
    }),
    
    actions: {
        async fetchActiveCharacter() {
            try {
                const response = await axios.get('/api/character');
                if (response.data.status === 'success') {
                    this.$patch(response.data.data);
                    this.isDead = this.hp <= 0;
                }
            } catch (error) {
                console.error("Error fetching character:", error);
            }
        },
        
        async heartbeatTick() {
            if (this.isDead) return;
            try {
                const response = await axios.post('/api/character/heartbeat');
                if (response.data.status === 'success') {
                    this.$patch(response.data.data.character);
                    if (this.hp <= 0) {
                        this.isDead = true;
                    }
                }
            } catch (error) {
                console.error("Error during heartbeat:", error);
            }
        }
    },
    
    persist: true,
});
