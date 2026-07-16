import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

// --- Reverb connection config, resolved at runtime ---------------------------
// The server renders the exact websocket endpoint into <meta> tags (key/host/
// port/scheme), so realtime works with only the runtime REVERB_* env — no Vite
// build-args required, and no fragile URL guessing:
//
//   • Dev:  meta → localhost:8080 (direct to the Reverb server).
//   • Prod: meta → the public domain over wss:443 (nginx proxies /app to Reverb).
//
// Vite vars and URL-derivation remain only as fallbacks for older builds.
const meta = (name) => document.querySelector(`meta[name="${name}"]`)?.content || undefined;
const loc = window.location;

// The server renders the exact websocket endpoint in <meta> tags (reverb-host/
// port/scheme), so this is correct in both dev and prod without build-time env.
// Fall back to Vite vars, then to deriving from the current URL.
const key = meta('reverb-key') || import.meta.env.VITE_REVERB_APP_KEY;
const host = meta('reverb-host') || import.meta.env.VITE_REVERB_HOST || loc.hostname;
const scheme = meta('reverb-scheme') || import.meta.env.VITE_REVERB_SCHEME || loc.protocol.replace(':', '');
const port = Number(meta('reverb-port') || import.meta.env.VITE_REVERB_PORT || (scheme === 'https' ? 443 : 80));

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
