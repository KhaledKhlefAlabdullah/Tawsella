import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: process.env.VITE_REVERB_APP_KEY,
    host: `${process.env.VITE_REVERB_SCHEME}://${process.env.VITE_REVERB_HOST}:${process.env.VITE_REVERB_PORT}`,
});
