import './bootstrap';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: 'c11c947859cefed20ca8',
    cluster: 'ap2',
    forceTLS: true,
});