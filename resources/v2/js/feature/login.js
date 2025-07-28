import { api } from '../util/axios.js';

export default function() {
    return {
        form: {
            email: '',
            password: ''
        },
        error: null,
        errors: {},
        loading: false,

        init() {
            // Alpine.js store 초기화
            if (!window.Alpine.store('login')) {
                window.Alpine.store('login', {
                    redirectUrl: null,
                    setRedirectUrl(url) {
                        if (url && !url.includes('/login')) {
                            this.redirectUrl = url;
                        }
                    }
                });
            }
            
            // 개발환경 체크
            this.checkDevelopmentMode();
        },
        
        // 개발환경 확인
        checkDevelopmentMode() {
            if (this.isDevelopment()) {
                console.log('🔧 개발환경 감지됨 - 세션 기반 인증 활성화');
                // 개발 모드 플래그를 세션에 설정
                if (typeof sessionStorage !== 'undefined') {
                    sessionStorage.setItem('dev_auth_enabled', 'true');
                }
            }
        },
        
        // 개발환경 확인 함수
        isDevelopment() {
            return window.location.hostname === 'localhost' || 
                   window.location.hostname === '127.0.0.1' ||
                   window.location.hostname.includes('local') ||
                   (typeof window.Laravel !== 'undefined' && window.Laravel.env === 'local');
        },

        async submit() {
            this.loading = true;
            this.errors = {};

            try {
                // 개발환경에서는 dev_mode 플래그 추가
                const formData = { ...this.form };
                if (this.isDevelopment()) {
                    formData.dev_mode = true;
                    console.log('🔧 개발모드 로그인 시도:', { email: formData.email });
                }
                
                const response = await api.post('/login', formData);
                
                // 개발환경 응답 확인
                if (response.data?.dev_mode) {
                    console.log('✅ 개발환경 세션 기반 로그인 성공:', response.data.message);
                }
                
                window.location.href = this.$store.login.redirectUrl || '/';
            } catch (error) {
                if (error.response?.data?.errors) {
                    this.errors = error.response.data.errors;
                    // 첫번째 필드에 포커스
                    const element = document.getElementById(Object.keys(this.errors)[0]);
                    if (element) {
                        element.focus();
                        element.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }
                
                // 개발환경에서 추가 정보 표시
                if (this.isDevelopment() && error.response?.data?.dev_mode) {
                    console.log('❌ 개발환경 로그인 실패:', error.response.data.message);
                }
            } finally {
                this.loading = false;
            }
        }
    }
}
