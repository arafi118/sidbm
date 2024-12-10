self.addEventListener('install', function (event) {
    console.log('install');

    event.waitUntil(
        caches.open('my-pwa-cache').then(function (cache) {
            return cache.addAll([
                '/',
                '/assets/img/icon/favicon.png',
            ]);
        })
    );
});

self.addEventListener('fetch', function (event) {
    console.log('Fecth');
    event.respondWith(
        caches.match(event.request).then(function (response) {
            return response || fetch(event.request);
        })
    );
});
