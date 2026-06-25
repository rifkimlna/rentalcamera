import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
    csrfToken: window.Laravel?.csrfToken,
});

window.Echo.connector.pusher.connection.bind('state_change', function (states) {
    console.log('[Echo] Connection:', states.previous, '→', states.current);
});

window.Echo.connector.pusher.connection.bind('error', function (err) {
    console.error('[Echo] Error:', err);
});
