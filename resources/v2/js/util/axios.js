import axios from 'axios';
import Swal from 'sweetalert2';

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
            console.log('FormData detected, setting multipart/form-data');
            // FormData 내용 로깅
            for (let pair of data.entries()) {
                console.log('FormData entry:', pair[0], pair[1] instanceof File ? pair[1].name : pair[1]);
            }
            headers['Content-Type'] = 'multipart/form-data';
            return data;
        }
        // 일반 데이터인 경우 JSON으로 변환
        console.log('Converting data to JSON');
        return JSON.stringify(data);
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
            console.log('Request interceptor: FormData detected');
            config.headers['Content-Type'] = 'multipart/form-data';
        }

        console.log('Request config:', {
            url: config.url,
            method: config.method,
            headers: config.headers,
            data: config.data instanceof FormData ? 'FormData' : config.data
        });

        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);

// 응답 인터셉터
instance.interceptors.response.use(
    (response) => {
        console.log('Response:', response);
        if (response.data?.status != 'ok') {
            Swal.fire({
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
            if (0 && error.response.status === 422) {
                // window.location.href = '/v2/login';
            } else {
                // status가 false인 경우 처리
                Swal.fire({
                    icon: 'error',
                    title: '오류 발생',
                    text: error.response.data?.message || '알 수 없는 오류가 발생했습니다.',
                    confirmButtonText: '확인'
                });
                return Promise.reject(error);
            }

            // // 401 Unauthorized 에러 처리
            // if (error.response.status === 401) {
            //     Swal.fire({
            //         icon: 'error',
            //         title: '권한 오류',
            //         text: '권한이 없습니다.',
            //         confirmButtonText: '확인'
            //     });
            // }

            // // 419 CSRF 토큰 만료
            // if (error.response.status === 419) {
            //     Swal.fire({
            //         icon: 'warning',
            //         title: '세션 만료',
            //         text: '페이지를 새로고침합니다.',
            //         showConfirmButton: false,
            //         timer: 1500
            //     }).then(() => {
            //         window.location.reload();
            //     });
            // }

            // // 500 서버 에러 처리
            // if (error.response.status === 500) {
            //     Swal.fire({
            //         icon: 'error',ㅁ
            //         title: '서버 오류',
            //         text: '서버에서 오류가 발생했습니다. 잠시 후 다시 시도해주세요.',
            //         confirmButtonText: '확인'
            //     });
            // }

            // 기타 에러 처리
            // if (![401, 419, 422, 500].includes(error.response.status)) {
            //     const customTitle = error.config && error.config.errorTitle ? error.config.errorTitle : '오류 발생';
            //     const errorMessages = error.response.data.message || '알 수 없는 오류가 발생했습니다.';
            //     Swal.fire({
            //         icon: 'error',
            //         title: customTitle,
            //         html: errorMessages,
            //         confirmButtonText: '확인'
            //     });
            // }
        } else if (error.request) {
            // 요청은 보냈지만 응답을 받지 못한 경우
            Swal.fire({
                icon: 'error',
                title: '네트워크 오류',
                text: '서버와의 통신에 실패했습니다. 인터넷 연결을 확인해주세요.',
                confirmButtonText: '확인'
            });
        } else {
            // 요청 설정 중 오류가 발생한 경우
            Swal.fire({
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

export default instance;
