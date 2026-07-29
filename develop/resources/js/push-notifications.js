function urlBase64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - (base64String.length % 4)) % 4);
    const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
    const rawData = window.atob(base64);
    return Uint8Array.from([...rawData].map((char) => char.charCodeAt(0)));
}

window.activarNotificacionesPush = async function (vapidPublicKey) {
    if (!('serviceWorker' in navigator) || !('PushManager' in window)) {
        alert('Tu navegador no soporta notificaciones de escritorio.');
        return;
    }

    try {
        const registro = await navigator.serviceWorker.register('/sw.js');

        const permiso = await Notification.requestPermission();
        if (permiso !== 'granted') {
            return;
        }

        const suscripcion = await registro.pushManager.subscribe({
            userVisibleOnly: true,
            applicationServerKey: urlBase64ToUint8Array(vapidPublicKey),
        });

        await axios.post('/push/subscribe', suscripcion.toJSON());

        alert('¡Notificaciones de escritorio activadas!');
    } catch (error) {
        console.error('Error activando notificaciones push:', error);
    }
};