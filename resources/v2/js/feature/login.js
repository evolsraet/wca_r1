import { api } from '../util/axios.js';

export default function() {
    return {
        form: {
            email: '',
            password: ''
        },
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
        },

        async submit() {
            this.loading = true;
            this.errors = {};

            try {
                const response = await api.post('/login', this.form);
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
            } finally {
                this.loading = false;
            }
        }
    }
}
