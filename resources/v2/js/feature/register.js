import { api } from '../util/axios.js';

export default function() {
    return {
        form: {
            name: '',
            email: '',
            phone: '',
            password: '',
            password_confirmation: '',
            receive_post: '',
            receive_addr1: '',
            receive_addr2: '',
            isDealer: false,
            dealer_name: '',
            dealerBirthDate: '',
            dealerContact: '',
            company: '',
            introduce: '',
            company_post: '',
            company_addr1: '',
            company_addr2: '',
            isDealerApplyCheck: false,
            isDealerApplyCheck1: false,
            isDealerApplyCheck2: false,
            isDealerApplyCheck3: false,
            car_management_business_registration_number: '',
            business_registration_number: '',
            corporation_registration_number: '',
            dealerCompanyDuty: '',
            file_user_photo: null,
            file_user_photo_name: '',
            file_user_biz: null,
            file_user_biz_name: '',
            file_user_sign: null,
            file_user_sign_name: '',
            file_user_cert: null,
            file_user_cert_name: ''
        },
        errors: {},
        loading: false,
        photoUrl: null,
        fileSignUrl: '',
        fileCertUrl: '',
        fileBizUrl: '',

        init(initialData = {}) {
            // 블레이드에서 전달받은 초기값 설정
            Object.assign(this.form, initialData);

            // 소셜 로그인 정보가 있으면 폼에 설정
            const urlParams = new URLSearchParams(window.location.search);
            const socialParam = urlParams.get('social');
            const emailParam = urlParams.get('email');
            const nameParam = urlParams.get('name');

            if (socialParam) {
                this.form.email = emailParam;
                this.form.name = nameParam;
            }
        },

        async submit() {
            this.loading = true;
            this.errors = {};

            try {
                const response = await api.post('/register', this.form);
                window.location.href = '/v2/login';
            } catch (error) {
                if (error.response?.data?.errors) {
                    this.errors = error.response.data.errors;
                    // 첫번째 필드에 포커스
                    const element = document.getElementById(Object.keys(this.errors)[0]);
                    if( element ) {
                        element.focus();
                        element.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }
            } finally {
                this.loading = false;
            }
        },

        // 파일 업로드 관련 메서드들
        handleFileUploadPhoto(event) {
            const file = event.target.files[0];
            if (file) {
                this.form.file_user_photo = file;
                this.form.file_user_photo_name = file.name;
                this.photoUrl = URL.createObjectURL(file);
            }
        },

        handleFileUploadBiz(event) {
            const file = event.target.files[0];
            if (file) {
                this.form.file_user_biz = file;
                this.form.file_user_biz_name = file.name;
                this.fileBizUrl = URL.createObjectURL(file);
            }
        },

        handleFileUploadSign(event) {
            const file = event.target.files[0];
            if (file) {
                this.form.file_user_sign = file;
                this.form.file_user_sign_name = file.name;
                this.fileSignUrl = URL.createObjectURL(file);
            }
        },

        handleFileUploadCert(event) {
            const file = event.target.files[0];
            if (file) {
                this.form.file_user_cert = file;
                this.form.file_user_cert_name = file.name;
                this.fileCertUrl = URL.createObjectURL(file);
            }
        },

        deletePhotoImg() {
            this.form.file_user_photo = null;
            this.form.file_user_photo_name = '';
            this.photoUrl = null;
        },

        // 주소 검색 관련 메서드
        editPostCode(elementName) {
            if (window.daum && window.daum.Postcode) {
                new window.daum.Postcode({
                    oncomplete: (data) => {
                        this.form.company_post = data.zonecode;
                        this.form.company_addr1 = data.address;
                    }
                }).embed(document.getElementById(elementName));
            }
        },

        editPostCodeReceive(elementName) {
            if (window.daum && window.daum.Postcode) {
                new window.daum.Postcode({
                    oncomplete: (data) => {
                        this.form.receive_post = data.zonecode;
                        this.form.receive_addr1 = data.address;
                    }
                }).embed(document.getElementById(elementName));
            }
        }
    }
}
