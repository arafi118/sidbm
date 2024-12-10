const CACHE_NAME = 'my-pwa-cache-v1';

self.addEventListener('install', function (event) {
    event.waitUntil(
        fetch('/cache-files')
        .then(response => response.json())
        .then(files => {
            return caches.open(CACHE_NAME).then(function (cache) {
                return cache.addAll(files);
            });
        })
    );
});

self.addEventListener('activate', function (event) {
    event.waitUntil(
        caches.keys().then(function (cacheNames) {
            return Promise.all(
                cacheNames.map(function (cacheName) {
                    if (cacheName !== CACHE_NAME) {
                        console.log('Deleting old cache:', cacheName);
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
});
