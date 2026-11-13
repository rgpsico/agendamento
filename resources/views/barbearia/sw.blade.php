// Service worker da fila do barbeiro: mostra o aviso mesmo com o app fechado e a tela apagada.
self.addEventListener('install', function () { self.skipWaiting(); });
self.addEventListener('activate', function (e) { e.waitUntil(self.clients.claim()); });

self.addEventListener('push', function (e) {
    var d = {};
    try { d = e.data ? e.data.json() : {}; } catch (_) {}
    e.waitUntil(self.registration.showNotification(d.titulo || 'Barbearia', {
        body: d.texto || 'Tem cliente na fila.',
        tag: d.tag || 'fila',
        renotify: true,
        requireInteraction: true,
        vibrate: [300, 150, 300, 150, 300],
        icon: '/images/barbearia/icone-192.png',
        badge: '/images/barbearia/icone-192.png',
        data: { url: d.url || '/barbearia/painel/fila' }
    }));
});

self.addEventListener('notificationclick', function (e) {
    e.notification.close();
    var url = (e.notification.data && e.notification.data.url) || '/barbearia/painel/fila';
    e.waitUntil(self.clients.matchAll({ type: 'window', includeUncontrolled: true }).then(function (janelas) {
        for (var i = 0; i < janelas.length; i++) {
            if (janelas[i].url.indexOf('/barbearia/painel/fila') !== -1 && 'focus' in janelas[i]) return janelas[i].focus();
        }
        return self.clients.openWindow(url);
    }));
});
