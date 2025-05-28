import axios from 'axios';
import Swal from 'sweetalert2';

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
            // Validation 에러 처리
            // if (error.response.data) {
            //     console.log('에러 응답 데이터:', error.response.data);

            //     // 서버 에러 객체 생성
            //     const serverErrors = {
            //         password: [error.response.data.message],
            //         phone: [error.response.data.message]
            //     };
            //     console.log('생성된 에러 객체:', serverErrors);

            //     // Alpine.js 컴포넌트에 에러 전달
            //     const component = window.Alpine.closestDataStack(form);
            //     console.log('Alpine 컴포넌트:', component);

            //     if (component && component.errors) {
            //         // 모든 에러 초기화
            //         Object.keys(component.errors).forEach(key => {
            //             component.errors[key] = [];
            //         });

            //         // 서버 에러 할당
            //         Object.keys(serverErrors).forEach(key => {
            //             if (component.errors.hasOwnProperty(key)) {
            //                 component.errors[key] = serverErrors[key];
            //             }
            //         });

            //         // 첫 번째 에러 필드에 포커스
            //         const firstErrorField = Object.keys(serverErrors)[0];
            //         console.log('첫 번째 에러 필드:', firstErrorField);
            //         console.log('서버 에러:', serverErrors);

            //         if (firstErrorField) {
            //             setTimeout(() => {
            //                 // 폼 내부에서 name 속성으로 요소 찾기
            //                 const element = form.querySelector(`[name="${firstErrorField}"]`);
            //                 console.log('찾은 요소:', element);
            //                 console.log('요소 name:', element?.name);
            //                 console.log('요소 type:', element?.type);

            //                 if (element) {
            //                     try {
            //                         element.focus();
            //                         element.scrollIntoView({ behavior: 'smooth', block: 'center' });
            //                         console.log('포커스 및 스크롤 완료');
            //                     } catch (e) {
            //                         console.error('포커스 실패:', e);
            //                     }
            //                 } else {
            //                     console.log('에러 필드를 찾을 수 없음');
            //                     // 폼 내의 모든 input 요소 로깅
            //                     console.log('폼 내 모든 input:', form.querySelectorAll('input'));
            //                 }
            //             }, 100);
            //         }
            //     }
            // }

            // 401 Unauthorized 에러 처리
            if (error.response.status === 401) {
                Swal.fire({
                    icon: 'error',
                    title: '권한 오류',
                    text: '권한이 없습니다.',
                    confirmButtonText: '확인'
                });
            }

            // 419 CSRF 토큰 만료
            if (error.response.status === 419) {
                Swal.fire({
                    icon: 'warning',
                    title: '세션 만료',
                    text: '페이지를 새로고침합니다.',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    window.location.reload();
                });
            }

            // 500 서버 에러 처리
            if (error.response.status === 500) {
                Swal.fire({
                    icon: 'error',
                    title: '서버 오류',
                    text: '서버에서 오류가 발생했습니다. 잠시 후 다시 시도해주세요.',
                    confirmButtonText: '확인'
                });
            }

            // 기타 에러 처리
            if (![401, 419, 422, 500].includes(error.response.status)) {
                const customTitle = error.config && error.config.errorTitle ? error.config.errorTitle : '오류 발생';
                const errorMessages = error.response.data.message || '알 수 없는 오류가 발생했습니다.';
                Swal.fire({
                    icon: 'error',
                    title: customTitle,
                    html: errorMessages,
                    confirmButtonText: '확인'
                });
            }
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
