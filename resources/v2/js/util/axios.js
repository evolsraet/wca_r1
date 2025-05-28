import axios from 'axios';
import Alpine from 'alpinejs';

// 파일 업로드 처리 함수
export const handleFileUpload = (event, component) => {
    const file = event.target.files[0];
    if (file) {
        const fieldName = event.target.name;
        // console.log(`File selected for ${fieldName}:`, file);

        component.form[fieldName] = file;
        component.form[fieldName + '_name'] = file.name;
        component.form[fieldName + '_url'] = URL.createObjectURL(file);

        console.log('File data set:', {
            fieldName,
            file: component.form[fieldName],
            fileName: component.form[fieldName + '_name'],
            previewUrl: component.form[fieldName + '_url']
        });
    }
};

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
            // for (let pair of data.entries()) {
            //     console.log('FormData entry:', pair[0], pair[1] instanceof File ? pair[1].name : pair[1]);
            // }
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

        // console.log('Request config:', {
        //     url: config.url,
        //     method: config.method,
        //     headers: config.headers,
        //     data: config.data instanceof FormData ? 'FormData' : config.data
        // });

        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);

// 응답 인터셉터
instance.interceptors.response.use(
    (response) => {
        console.debug('Response:', response);
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
                // console.log(error.response.data.errors);

                // 첫번째 에러 필드로 포커스
                const models = Object.keys(error.response.data.errors);
                if (models.length > 0) {
                    const firstModel = models[0];
                    const firstField = Object.keys(error.response.data.errors[firstModel])[0];
                    // console.log('첫번째 모델:', firstModel);
                    // console.log('첫번째 필드:', firstField);
                    const elementName = firstModel + '.' + firstField;
                    const element = document.querySelector(`[name="${elementName}"]`);
                    // console.log('elementName:', elementName);
                    // console.log('element:', element);
                    if( element ) {
                        element.focus();
                        element.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }
            }

            if (error.response.status === 422) {
                // console.log('422');
                Alpine.store('toastr').error(error.response.data?.message || '입력 값을 확인 해 주세요');
                // window.location.href = '/v2/login';
            // 419 CSRF 토큰 만료
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
                // status가 false인 경우 처리
                Alpine.store('swal').fire({
                    icon: 'error',
                    title: '오류 발생',
                    text: error.response.data?.message || '알 수 없는 오류가 발생했습니다.',
                    confirmButtonText: '확인'
                });
                return Promise.reject(error);
            }
        } else if (error.request) {
            // 요청은 보냈지만 응답을 받지 못한 경우
            Alpine.store('swal').fire({
                icon: 'error',
                title: '네트워크 오류',
                text: '서버와의 통신에 실패했습니다. 인터넷 연결을 확인해주세요.',
                confirmButtonText: '확인'
            });
        } else {
            // 요청 설정 중 오류가 발생한 경우
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

export default instance;
