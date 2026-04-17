import { defineStore } from 'pinia';
import axios from 'axios';

export const useLocationStore = defineStore('locations', {
    state: () => ({
        locations: [],
        isLoading: false,
    }),

    actions: {
        async fetchLocations() {
            this.isLoading = true;
            try {
                const response = await axios.get('/api/locations');
                if (response.data.status === 'success') {
                    this.locations = response.data.data;
                }
            } catch (error) {
                console.error('Error fetching locations:', error);
            } finally {
                this.isLoading = false;
            }
        },
    },
});
