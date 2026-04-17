import { defineStore } from 'pinia';
import axios from 'axios';

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: null,
        token: null,
    }),
    
    getters: {
        isAuthenticated: (state) => !!state.token,
    },
    
    actions: {
        async login(credentials) {
            const response = await axios.post('/api/login', credentials);
            this.user = response.data.user;
            this.token = response.data.token;
            this.setAxiosToken();
        },
        
        async register(userData) {
            const response = await axios.post('/api/register', userData);
            this.user = response.data.user;
            this.token = response.data.token;
            this.setAxiosToken();
        },
        
        async logout() {
            try {
                await axios.post('/api/logout');
            } catch (error) {
                console.error('Logout error', error);
            } finally {
                this.clearAuth();
            }
        },

        clearAuth() {
            this.user = null;
            this.token = null;
            axios.defaults.headers.common['Authorization'] = null;
        },
        
        setAxiosToken() {
            if (this.token) {
                axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
            }
        }
    },
    
    persist: true,
});
