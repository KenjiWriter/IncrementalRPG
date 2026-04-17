import axios from 'axios';
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

window.axios.interceptors.response.use(
    response => response,
    error => {
        if (error.response && error.response.status === 401) {
            localStorage.removeItem('auth');
            if (window.location.pathname !== '/') {
                window.location.href = '/';
            }
        }
        return Promise.reject(error);
    }
);

/**
 * Laravel Echo — Reverb WebSocket connection.
 *
 * Uses the Pusher protocol under the hood (Reverb is Pusher-compatible).
 * Private channel auth is handled by posting to /broadcasting/auth via the
 * existing axios instance, which already carries the Sanctum XSRF cookie.
 */
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: parseInt(import.meta.env.VITE_REVERB_PORT ?? '8080'),
    wssPort: parseInt(import.meta.env.VITE_REVERB_PORT ?? '8080'),
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'http') === 'https',
    enabledTransports: ['ws', 'wss'],
    // Authorise private channels using the Sanctum-protected /broadcasting/auth endpoint
    authorizer: (channel) => ({
        authorize: (socketId, callback) => {
            window.axios
                .post('/broadcasting/auth', {
                    socket_id: socketId,
                    channel_name: channel.name,
                })
                .then(response => callback(false, response.data))
                .catch(error => callback(true, error));
        },
    }),
});
