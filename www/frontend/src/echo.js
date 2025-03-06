import Echo from "laravel-echo";
import Pusher from "pusher-js";
window.Pusher = Pusher;

window.Echo = new Echo({
  broadcaster: 'reverb',
  key: import.meta.env.VITE_REVERB_APP_KEY || 'localkey',
  wsHost: import.meta.env.VITE_REVERB_HOST || window.location.hostname,
  wsPort: import.meta.env.VITE_REVERB_PORT || 6001,
  forceTLS: false,
  disableStats: true,
  enabledTransports: ['ws'],
  // auth: {
  //   headers: {
  //     Authorization: `Bearer ${token}`
  //   }
  // }
});
