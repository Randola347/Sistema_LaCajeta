const CACHE_NAME = "lacajeta-cache-v1";
const OFFLINE_URL = "/offline.html";

// Guardamos la última URL visitada
let lastUrl = "/ventas";

self.addEventListener("install", event => {
    event.waitUntil(
        caches.open(CACHE_NAME).then(cache => {
            return cache.addAll([
                OFFLINE_URL,
                "/icons/icon-192x192.png",
                "/icons/icon-512x512.png"
            ]);
        })
    );
    self.skipWaiting();
});

self.addEventListener("activate", event => {
    event.waitUntil(
        caches.keys().then(keys =>
            Promise.all(keys.filter(k => k !== CACHE_NAME).map(k => caches.delete(k)))
        )
    );
    self.clients.claim();
});

// Interceptar requests de navegación
self.addEventListener("fetch", event => {
    if (event.request.mode === "navigate") {
        lastUrl = event.request.url; // ✅ guardamos la última URL visitada
        event.respondWith(
            fetch(event.request).catch(() => caches.match(OFFLINE_URL))
        );
    }
});

// Redirigir al volver la conexión
self.addEventListener("sync", event => {
    if (event.tag === "online-sync") {
        event.waitUntil(
            self.clients.matchAll().then(clients => {
                clients.forEach(client => {
                    client.navigate(lastUrl); // ✅ volvemos a la última URL
                });
            })
        );
    }
});

// Escuchar cuando el navegador detecta que volvió la conexión
self.addEventListener("message", event => {
    if (event.data === "online") {
        self.registration.sync.register("online-sync");
    }
});
