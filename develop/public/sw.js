self.addEventListener('push', function (event) {
    if (!event.data) return;

    const data = event.data.json();

    const titulo = data.title || 'GestionTICS';
    const opciones = {
        body: data.body || '',
        icon: data.icon || '/favicon.ico',
        badge: '/favicon.ico',
        data: {
            url: data.url || '/',
        },
    };

    event.waitUntil(self.registration.showNotification(titulo, opciones));
});

// Al hacer clic en la notificación, enfoca o abre la pestaña de la app
self.addEventListener('notificationclick', function (event) {
    event.notification.close();

    event.waitUntil(
        clients.matchAll({ type: 'window' }).then(function (clientList) {
            for (const client of clientList) {
                if (client.url.includes(self.location.origin) && 'focus' in client) {
                    return client.focus();
                }
            }
            if (clients.openWindow) {
                return clients.openWindow(event.notification.data.url);
            }
        })
    );
});