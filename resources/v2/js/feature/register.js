export default function() {
    return {
        form: {
            user: {
                name: '1',
                email: 'a@b.com',
                phone: '222',
                password: '123123123',
                password_confirmation: '123123123',
                socialLogin: false,
                // role 체크시 dealer 로
            },
            isDealer: true,
            dealer: {
                name: '',
                phone: '',
                birthday: '',
                company: '',
                company_duty: '',
                company_post: '',
                company_addr1: '',
                company_addr2: '',
                receive_post: '',
                receive_addr1: '',
                receive_addr2: '',
                introduce: '',
                business_registration_number: '',
                corporation_registration_number: '',
                car_management_business_registration_number: '',
                business_identity_number: '',
            },
            file_user_photo: null,
            file_user_photo_name: '',
            file_user_photo_url: '',
            file_user_biz: null,
            file_user_biz_name: '',
            file_user_biz_url: '',
            file_user_sign: null,
            file_user_sign_name: '',
            file_user_sign_url: '',
            file_user_cert: null,
            file_user_cert_name: '',
            file_user_cert_url: '',
            isDealerApply1: false,
            isDealerApply2: false,
            isDealerApply3: false,
            isDealerApply4: false,
            isPrivacyCheck: false,
        },
        errors: {},
        loading: false,
        photoUrl: null,
        fileSignUrl: '',
        fileCertUrl: '',
        fileBizUrl: '',

        init(initialData = {}) {
            Object.assign(this.form, initialData);

            const urlParams = new URLSearchParams(window.location.search);
            const socialParam = urlParams.get('social');
            const emailParam = urlParams.get('email');
            const nameParam = urlParams.get('name');

            if (socialParam) {
                this.form.email = emailParam;
                this.form.name = nameParam;
            }

            // 파일 이벤트 리스너 등록
            this.$el.addEventListener('file-selected', (event) => {
                const { fieldName, file, fileName, previewUrl } = event.detail;

                // 멀티플 파일 처리
                if (fieldName.endsWith('[]')) {
                    const baseFieldName = fieldName.slice(0, -2);
                    if (!this.form[baseFieldName]) {
                        this.form[baseFieldName] = [];
                    }
                    if (!this.form[`${baseFieldName}_name`]) {
                        this.form[`${baseFieldName}_name`] = [];
                    }
                    if (!this.form[`${baseFieldName}_url`]) {
                        this.form[`${baseFieldName}_url`] = [];
                    }

                    this.form[baseFieldName].push(file);
                    this.form[`${baseFieldName}_name`].push(fileName);
                    this.form[`${baseFieldName}_url`].push(previewUrl);
                } else {
                    // 단일 파일 처리
                    this.form[fieldName] = file;
                    this.form[`${fieldName}_name`] = fileName;
                    this.form[`${fieldName}_url`] = previewUrl;
                }
            });

            this.$el.addEventListener('file-removed', (event) => {
                const { fieldName, index } = event.detail;

                // 멀티플 파일 처리
                if (fieldName.endsWith('[]')) {
                    const baseFieldName = fieldName.slice(0, -2);
                    if (index !== undefined) {
                        this.form[baseFieldName].splice(index, 1);
                        this.form[`${baseFieldName}_name`].splice(index, 1);
                        this.form[`${baseFieldName}_url`].splice(index, 1);
                    } else {
                        this.form[baseFieldName] = [];
                        this.form[`${baseFieldName}_name`] = [];
                        this.form[`${baseFieldName}_url`] = [];
                    }
                } else {
                    // 단일 파일 처리
                    this.form[fieldName] = null;
                    this.form[`${fieldName}_name`] = '';
                    this.form[`${fieldName}_url`] = '';
                }
            });
        },

        async submit() {
            this.loading = true;
            this.errors = {};

            try {
                const formData = new FormData();

                // user 데이터 추가
                Object.keys(this.form.user).forEach(key => {
                    formData.append(`user[${key}]`, this.form.user[key]);
                });

                // dealer 데이터 추가
                if (this.form.isDealer) {
                    formData.append(`user[role]`, 'dealer');
                    Object.keys(this.form.dealer).forEach(key => {
                        formData.append(`dealer[${key}]`, this.form.dealer[key]);
                    });

                    // 파일 데이터 추가
                    const fileFields = [
                        'file_user_photo',
                        'file_user_biz',
                        'file_user_sign',
                        'file_user_cert'
                    ];

                    fileFields.forEach(fieldName => {
                        const files = this.form[fieldName];
                        if (Array.isArray(files)) {
                            // 멀티플 파일 처리
                            files.forEach((file, index) => {
                                if (file instanceof File) {
                                    formData.append(`${fieldName}[]`, file);
                                }
                            });
                        } else if (files instanceof File) {
                            // 단일 파일 처리
                            formData.append(fieldName, files);
                        }
                    });
                }

                const response = await this.$store.api.post('/api/users', formData);
                window.location.href = '/v2/login';
            } catch (error) {
                if (error.response?.data?.errors) {
                    this.errors = error.response.data.errors;
                }
            } finally {
                this.loading = false;
            }
        },

        deletePhotoImg() {
            this.form.file_user_photo = null;
            this.photoUrl = null;
        }
    }
}
