import axios from 'axios';

// axios 인스턴스 생성
const instance = axios.create({
    baseURL: '',  // v2 prefix 사용
    headers: {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
    },
    withCredentials: true,  // sanctum 인증을 위한 설정
    transformRequest: [(data, headers) => {
        // FormData인 경우 Content-Type을 multipart/form-data로 설정
        if (data instanceof FormData) {
            headers['Content-Type'] = 'multipart/form-data';
            return data;
        }

        // 이미 문자열인 경우 그대로 반환
        if (typeof data === 'string') {
            return data;
        }

        // 일반 객체인 경우 JSON으로 변환
        if (typeof data === 'object' && data !== null) {
            headers['Content-Type'] = 'application/json';
            return JSON.stringify(data);
        }

        return data;
    }]
});

// 요청 인터셉터
instance.interceptors.request.use(
    (config) => {
        // CSRF 토큰 설정
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (token) {
            config.headers['X-CSRF-TOKEN'] = token;
        }

        // FormData인 경우 Content-Type 설정
        if (config.data instanceof FormData) {
            config.headers['Content-Type'] = 'multipart/form-data';

            // PUT 요청일 때 _method 필드 추가
            if (config.method === 'put') {
                config.data.append('_method', 'PUT');
                // PUT 요청을 POST로 변경
                config.method = 'post';
            }
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
        if (response.data?.status != 'ok') {
            Alpine.store('swal').fire({
                icon: 'error',
                title: '오류 발생 (응답 오류)',
                text: response.data?.message || '알 수 없는 오류가 발생했습니다.',
                confirmButtonText: '확인'
            });
        } else {
            return response;
        }
    },
    (error) => {
        if (error.response && error.response.data?.status) {
            if( error.response.data?.errors ) {
                const models = Object.keys(error.response.data.errors);
                if (models.length > 0) {
                    const firstModel = models[0];
                    const firstField = Object.keys(error.response.data.errors[firstModel])[0];
                    const elementName = firstModel + '.' + firstField;
                    const element = document.querySelector(`[name="${elementName}"]`);
                    if( element ) {
                        element.focus();
                        element.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }
            }

            if (error.response.status === 422) {
                Alpine.store('toastr').error(error.response.data?.message || '입력 값을 확인 해 주세요');
            } else if (error.response.status === 419) {
                Alpine.store('swal').fire({
                    icon: 'warning',
                    title: '세션 만료',
                    text: '페이지를 새로고침합니다.',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    window.location.reload();
                });
            } else {
                Alpine.store('swal').fire({
                    icon: 'error',
                    title: '오류 발생',
                    text: error.response.data?.message || '알 수 없는 오류가 발생했습니다.',
                    confirmButtonText: '확인'
                });
                return Promise.reject(error);
            }
        } else if (error.request) {
            Alpine.store('swal').fire({
                icon: 'error',
                title: '네트워크 오류',
                text: '서버와의 통신에 실패했습니다. 인터넷 연결을 확인해주세요.',
                confirmButtonText: '확인'
            });
        } else {
            Alpine.store('swal').fire({
                icon: 'error',
                title: '요청 오류',
                text: '요청을 처리하는 중 오류가 발생했습니다.',
                confirmButtonText: '확인'
            });
        }
        return Promise.reject(error);
    }
);

// API 요청 메서드
export const api = {
    get: (url, params = {}, config = {}) => instance.get(url, { ...config, params }),
    post: (url, data = {}, config = {}) => instance.post(url, data, config),
    put: (url, data = {}, config = {}) => instance.put(url, data, config),
    patch: (url, data = {}, config = {}) => instance.patch(url, data, config),
    delete: (url, config = {}) => instance.delete(url, config)
};

// FormData 처리 함수
export const appendFormData = (formData, formElements) => {
    Array.from(formElements).forEach(element => {
        if (element.name && !element.disabled) {  // name 속성이 있고 disabled가 아닌 요소만 처리
            if (element.name.includes('.')) {
                const [key, value] = element.name.split('.');
                if (element.type === 'checkbox') {
                    if (element.checked) {
                        formData.append(`${key}[${value}]`, 1);  // 숫자 1로 전송
                    }
                } else {
                    formData.append(`${key}[${value}]`, element.value);
                }
            }
        }
    });
    return formData;
};

export default instance;
