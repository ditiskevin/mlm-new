import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

// Reverb speaks the Pusher protocol, so we use the pusher-js client.
window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
    // Authorize private channels through axios so the XSRF-TOKEN cookie is
    // sent automatically (no csrf-token meta tag needed in the Inertia app).
    authorizer: (channel) => ({
        authorize: (socketId, callback) => {
            window.axios
                .post('/broadcasting/auth', {
                    socket_id: socketId,
                    channel_name: channel.name,
                })
                .then((response) => callback(null, response.data))
                .catch((error) => callback(error));
        },
    }),
});
