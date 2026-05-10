const CACHE_NAME = 'lumo-v2';
const STATIC_ASSETS = ['/manifest.json'];

self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME).then(cache => cache.addAll(STATIC_ASSETS))
    );
    self.skipWaiting();
});

self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(keys =>
            Promise.all(keys.filter(k => k !== CACHE_NAME).map(k => caches.delete(k)))
        )
    );
    self.clients.claim();
});

self.addEventListener('fetch', event => {
    const request = event.request;

    if (request.mode === 'navigate') {
        event.respondWith(
            fetch(request)
                .then(response => {
                    const responseClone = response.clone();
                    caches.open(CACHE_NAME).then(cache => cache.put(request, responseClone));
                    return response;
                })
                .catch(() => caches.match(request).then(cached => cached || caches.match('/')))
        );

        return;
    }

    if (event.request.url.includes('/api/')) {
        event.respondWith(
            fetch(event.request).catch(() => caches.match(event.request))
        );
    } else {
        event.respondWith(
            caches.match(event.request).then(response => {
                if (response) {
                    return response;
                }

                return fetch(event.request).then(networkResponse => {
                    const clone = networkResponse.clone();
                    caches.open(CACHE_NAME).then(cache => cache.put(event.request, clone));
                    return networkResponse;
                });
            })
        );
    }
});
