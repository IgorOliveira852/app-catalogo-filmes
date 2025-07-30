import { defineStore } from 'pinia';
import axiosClient from '../axios.js';

export const useUserStore = defineStore('user', {
    state: () => ({
        user: null,
        loading: false,
        error: null,
    }),
    getters: {
        isLoggedIn: (state) => !!state.user,
    },
    actions: {
        async fetchUser() {
            this.loading = true;
            this.error = null;
            try {
                const { data } = await axiosClient.get('/user');
                this.user = data;
            } catch (err) {
                this.user = null;
                this.error = err.response?.data?.message || err.message;
                throw err;            // para o guard do router captar a falha
            } finally {
                this.loading = false;
            }
        },

        async login(credentials) {
            this.loading = true;
            this.error = null;
            try {
                const { data } = await axiosClient.post('/login', credentials);
                localStorage.setItem('AUTH_TOKEN', data.token);
                axiosClient.defaults.headers.common['Authorization'] = `Bearer ${data.token}`;
                await this.fetchUser();
            } catch (err) {
                this.error = err.response?.data?.message || err.message;
                throw err;
            } finally {
                this.loading = false;
            }
        },

        logout() {
            localStorage.removeItem('AUTH_TOKEN');
            delete axiosClient.defaults.headers.common['Authorization'];
            this.user = null;
        }
    }
});