import axios from 'axios';

// axios 인스턴스 생성
const instance = axios.create({
    baseURL: '/v2',  // v2 prefix 사용
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
    },
    withCredentials: true  // sanctum 인증을 위한 설정
});

// 요청 인터셉터
instance.interceptors.request.use(
    (config) => {
        // CSRF 토큰 설정
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (token) {
            config.headers['X-CSRF-TOKEN'] = token;
        }
        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);

// 응답 인터셉터
instance.interceptors.response.use(
    (response) => {
        return response;
    },
    (error) => {
        if (error.response) {
            // 401 Unauthorized 에러 처리
            if (error.response.status === 401) {
                window.location.href = '/login';
            }

            // 419 CSRF 토큰 만료
            if (error.response.status === 419) {
                window.location.reload();
            }
        }
        return Promise.reject(error);
    }
);

// API 요청 메서드
export const api = {
    get: (url, params = {}) => instance.get(url, { params }),
    post: (url, data = {}) => instance.post(url, data),
    put: (url, data = {}) => instance.put(url, data),
    patch: (url, data = {}) => instance.patch(url, data),
    delete: (url) => instance.delete(url)
};

export default instance;
