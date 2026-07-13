// MommyLovesMe.nl — minimal service worker for installability + a friendly
// offline fallback. It deliberately does NOT cache API/Inertia responses, so
// authenticated data is always fresh.
const CACHE = 'mlm-shell-v1';
const OFFLINE_URL = '/offline.html';

self.addEventListener('install', (event) => {
    event.waitUntil(caches.open(CACHE).then((cache) => cache.addAll([OFFLINE_URL])));
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((keys) => Promise.all(keys.filter((k) => k !== CACHE).map((k) => caches.delete(k)))),
    );
    self.clients.claim();
});

self.addEventListener('fetch', (event) => {
    const req = event.request;
    // Only handle top-level navigations; everything else goes straight to network.
    if (req.mode !== 'navigate') return;

    event.respondWith(
        fetch(req).catch(() => caches.match(OFFLINE_URL)),
    );
});
