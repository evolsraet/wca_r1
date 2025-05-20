import _ from "lodash";
window._ = _;

import "bootstrap";

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "" token cookie.
 */

import axios from "axios";
window.axios = axios;

import store from "./store";

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
window.axios.defaults.withCredentials = true;
window.axios.interceptors.response.use(
    (response) => response,
    (error) => {
        if (
            // error.response?.status === 500 ||
            error.response?.status === 401 ||
            error.response?.status === 403 ||
            error.response?.status === 419
        ) {
            localStorage.clear;
            store.dispatch("auth/logout");
            alert('장시간 미사용으로 로그인 정보가 올바르지 않습니다.\n로그아웃을 진행합니다.');
            location.assign("/login");
            /*
            //TODO: 기존 로직시 무한로딩에 빠지는 문제가 있어 로그아웃 처리로 변경했음, 이대로 문제 없는지 체크 필요
            if (location.pathname !== "/login") {
                location.assign("/login");
            }
            */
        }
        return Promise.reject(error);
    }
);

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// import Pusher from 'pusher-js';
// window.Pusher = Pusher;

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: import.meta.env.VITE_PUSHER_APP_KEY,
//     wsHost: import.meta.env.VITE_PUSHER_HOST ?? `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
//     wsPort: import.meta.env.VITE_PUSHER_PORT ?? 80,
//     wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,
//     forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https',
//     enabledTransports: ['ws', 'wss'],
// });
