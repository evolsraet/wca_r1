import { appendFormData, appendFilesToFormData, setupFileUploadListeners } from '../util/fileUpload';

export default function() {
    return {
        form: {
            user: {
                name: '',
                email: '',
                phone: '',
                password: '',
                password_confirmation: '',
                // socialLogin: false,
                // role 체크시 dealer 로
            },
            isDealer: true,
            dealer: {
            //     name: '',
            //     phone: '',
            //     birthday: '',
            //     company: '',
            //     company_duty: '',
            //     company_post: '',
            //     company_addr1: '',
            //     company_addr2: '',
            //     receive_post: '',
            //     receive_addr1: '',
            //     receive_addr2: '',
            //     introduce: '',
            //     business_registration_number: '',
            //     corporation_registration_number: '',
            //     car_management_business_registration_number: '',
            //     business_identity_number: '',
            },

            // isDealerApply1: false,
            // isDealerApply2: false,
            // isDealerApply3: false,
            // isDealerApply4: false,
            // isPrivacyCheck: false,
        },
        errors: {},
        loading: false,

        init(initialData = {}) {

            if (typeof initialData === 'object' && initialData !== null && Object.keys(initialData).length > 0) {
                Object.assign(this.form, initialData);
            } else {
            }

            const urlParams = new URLSearchParams(window.location.search);
            const socialParam = urlParams.get('social');
            const emailParam = urlParams.get('email');
            const nameParam = urlParams.get('name');

            if (socialParam) {
                this.form.user.email = emailParam;
                this.form.user.name = nameParam;
            }

            // 파일 업로드 이벤트 리스너 설정 (필수설정)
            setupFileUploadListeners(this.form, this.$el);
        },

        consoleForm() {
            console.log(this.form);
        },

        async submit() {
            this.loading = true;
            this.errors = {};

            try {
                const formData = new FormData();

                // 폼 요소들 가져오기
                const formElements = this.$el.elements;
                appendFormData(formData, formElements);

                if (this.form.isDealer) {
                    if( this.form.isUpdate ) {
                        if( !confirm("딜러 정보를 변경하시겠습니까?\n변경 시 재심사가 필요합니다.") ) {
                            return;
                        }
                    } else {
                        formData.append(`user[role]`, 'dealer');
                    }
                }

                const fileFields = [
                    'file_user_photo',
                    'file_user_biz',
                    'file_user_sign',
                    'file_user_cert'
                ];

                appendFilesToFormData(formData, fileFields, this.$el);

                // for (let [key, value] of formData.entries()) {
                //     console.log(`${key}: ${value}`);
                // }

                if( this.form.isUpdate ) {
                    const response = await this.$store.api.put('/api/users/' + this.form.user.id, formData);
                    Alpine.store('swal').fire({
                        title: '수정 완료',
                        text: response.data.message,
                        icon: 'success',
                        confirmButtonText: '확인'
                    }).then((result) => {
                        if (response.data?.data?.redirect) {
                            window.location.href = response.data.data.redirect;
                        }
                    });
                } else {
                    const response = await this.$store.api.post('/api/users', formData);
                    Alpine.store('swal').fire({
                        title: '회원가입 완료',
                        text: response.data.message,
                        icon: 'success',
                        confirmButtonText: '확인'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '/v2/login';
                        }
                    });
                }

            } catch (error) {
                if (error.response?.data?.errors) {
                    this.errors = error.response.data.errors;
                }
            } finally {
                this.loading = false;
            }
        }
    }
}
