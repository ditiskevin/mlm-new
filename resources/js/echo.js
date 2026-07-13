import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

// --- Reverb connection config, resolved at runtime ---------------------------
// The app key is rendered into a <meta> tag by the server, so realtime works in
// production with only the runtime REVERB_* env — no Vite build-args required.
//
//   • Dev: the VITE_REVERB_* vars (localhost:8080) are baked at build time and
//     take precedence.
//   • Prod: with no VITE vars, we derive host/port/scheme from the current URL,
//     since nginx proxies the Reverb websocket at /app on the same domain.
const meta = (name) => document.querySelector(`meta[name="${name}"]`)?.content || undefined;

const key = meta('reverb-key') || import.meta.env.VITE_REVERB_APP_KEY;
const viteHost = import.meta.env.VITE_REVERB_HOST;
const loc = window.location;

const host = viteHost || loc.hostname;
const scheme = viteHost ? (import.meta.env.VITE_REVERB_SCHEME ?? 'https') : loc.protocol.replace(':', '');
const port = viteHost
    ? Number(import.meta.env.VITE_REVERB_PORT ?? (scheme === 'https' ? 443 : 80))
    : loc.protocol === 'https:'
      ? 443
      : 80;

// Only wire up Echo when we actually have a key (avoids console errors when
// broadcasting isn't configured, e.g. local setups without Reverb running).
if (key) {
    window.Echo = new Echo({
        broadcaster: 'reverb',
        key,
        wsHost: host,
        wsPort: port,
        wssPort: port,
        forceTLS: scheme === 'https',
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
}
