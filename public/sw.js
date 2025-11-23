const CACHE_NAME = 'pos-warung-v1';
const urlsToCache = [
    '/',
    '/dashboard',
    '/cashier',
    '/products',
    '/transactions',
    '/css/app.css',
    '/js/app.js',
    '/js/indexeddb.js',
    '/js/sync.js'
];

// Install Service Worker
self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => {
                console.log('Opened cache');
                return cache.addAll(urlsToCache);
            })
    );
    self.skipWaiting();
});

// Activate Service Worker
self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.map(cacheName => {
                    if (cacheName !== CACHE_NAME) {
                        console.log('Deleting old cache:', cacheName);
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
    self.clients.claim();
});

// Fetch Strategy: Network First, fallback to Cache
self.addEventListener('fetch', event => {
    // Skip non-GET requests
    if (event.request.method !== 'GET') {
        return;
    }

    event.respondWith(
        fetch(event.request)
            .then(response => {
                // Clone response before caching
                const responseToCache = response.clone();

                caches.open(CACHE_NAME)
                    .then(cache => {
                        cache.put(event.request, responseToCache);
                    });

                return response;
            })
            .catch(() => {
                // Network failed, try cache
                return caches.match(event.request)
                    .then(response => {
                        if (response) {
                            return response;
                        }
                        
                        // Return offline page for navigation requests
                        if (event.request.mode === 'navigate') {
                            return caches.match('/offline.html');
                        }
                    });
            })
    );
});

// Background Sync for offline transactions
self.addEventListener('sync', event => {
    if (event.tag === 'sync-transactions') {
        event.waitUntil(syncTransactions());
    }
});

async function syncTransactions() {
    // This will be handled by sync.js when online
    console.log('Background sync triggered');
}

// Push Notification (optional for future use)
self.addEventListener('push', event => {
    const data = event.data ? event.data.json() : {};
    const title = data.title || 'POS Warung';
    const options = {
        body: data.body || 'Anda memiliki notifikasi baru',
        icon: '/icon.png',
        badge: '/badge.png'
    };

    event.waitUntil(
        self.registration.showNotification(title, options)
    );
});