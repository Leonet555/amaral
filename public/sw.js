const CACHE_NAME = 'torneios-combat-v1';
const urlsToCache = ['/', '/login', '/athlete', '/organizer', '/dashboard'];

self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then((cache) => cache.addAll(urlsToCache))
      .then(() => self.skipWaiting())
      .catch(() => {})
  );
});

self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((keys) =>
      Promise.all(keys.filter((k) => k !== CACHE_NAME).map((k) => caches.delete(k)))
    ).then(() => self.clients.claim())
  );
});

self.addEventListener('fetch', (event) => {
  if (event.request.method !== 'GET') return;
  if (event.request.url.includes('/api/')) return;
  event.respondWith(
    caches.match(event.request).then((cached) =>
      cached || fetch(event.request).then((res) => {
        const clone = res.clone();
        if (res.status === 200 && event.request.url.startsWith(self.location.origin))
          caches.open(CACHE_NAME).then((c) => c.put(event.request, clone));
        return res;
      })
    )
  );
});
