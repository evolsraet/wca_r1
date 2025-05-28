import { api } from '../util/axios.js';
import Swal from 'sweetalert2';

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
                isDealerApplyCheck: false,
                isDealerApplyCheck1: false,
                isDealerApplyCheck2: false,
                isDealerApplyCheck3: false
            },
            file_user_photo: null,
            file_user_photo_name: '',
            file_user_biz: null,
            file_user_biz_name: '',
            file_user_sign: null,
            file_user_sign_name: '',
            file_user_cert: null,
            file_user_cert_name: '',
            // name: '',
            // email: '',
            // phone: '',
            // password: '',
            // password_confirmation: '',
            // receive_post: '',
            // receive_addr1: '',
            // receive_addr2: '',
            // isDealer: false,
            // dealer_name: '',
            // dealerBirthDate: '',
            // dealerContact: '',
            // company: '',
            // introduce: '',
            // company_post: '',
            // company_addr1: '',
            // company_addr2: '',
            // isDealerApplyCheck: false,
            // isDealerApplyCheck1: false,
            // isDealerApplyCheck2: false,
            // isDealerApplyCheck3: false,
            // car_management_business_registration_number: '',
            // business_registration_number: '',
            // corporation_registration_number: '',
            // dealerCompanyDuty: '',
            // file_user_photo: null,
            // file_user_photo_name: '',
            // file_user_biz: null,
            // file_user_biz_name: '',
            // file_user_sign: null,
            // file_user_sign_name: '',
            // file_user_cert: null,
            // file_user_cert_name: ''
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

            // 파일 업로드 이벤트 리스너 등록
            // this.$el.addEventListener('file-upload', (event) => {
            //     const { name, event: fileEvent } = event.detail;
            //     const file = fileEvent.target.files[0];

            //     if (file) {
            //         console.log('File upload event received:', {
            //             name,
            //             fileName: file.name,
            //             fileType: file.type,
            //             fileSize: file.size
            //         });

            //         // 파일 필드에 따라 처리
            //         this.form[name] = file;

            //         // 미리보기 URL 설정
            //         if (name === 'file_user_photo') {
            //             this.photoUrl = URL.createObjectURL(file);
            //         } else if (name === 'file_user_biz') {
            //             this.fileBizUrl = URL.createObjectURL(file);
            //         } else if (name === 'file_user_sign') {
            //             this.fileSignUrl = URL.createObjectURL(file);
            //         } else if (name === 'file_user_cert') {
            //             this.fileCertUrl = URL.createObjectURL(file);
            //         }

            //         // 파일이 제대로 설정되었는지 확인
            //         // console.log('After setting file:', {
            //         //     name,
            //         //     file: this.form[name],
            //         //     formData: this.form
            //         // });
            //     }
            // });
        },

        async submit() {
            this.loading = true;
            this.errors = {};

            try {
                // FormData 생성
                const formData = new FormData();

                // user 데이터 추가
                Object.keys(this.form.user).forEach(key => {
                    formData.append(`user[${key}]`, this.form.user[key]);
                });

                // dealer 데이터 추가
                Object.keys(this.form.dealer).forEach(key => {
                    formData.append(`dealer[${key}]`, this.form.dealer[key]);
                });

                // 파일 데이터 추가
                const fileFields = ['file_user_photo', 'file_user_biz', 'file_user_sign', 'file_user_cert'];
                fileFields.forEach(key => {
                    if (this.form[key]) {
                        console.log(`Adding file ${key}:`, this.form[key]);
                        formData.append(key, this.form[key]);
                    }
                });

                // FormData 내용 확인
                // console.log('FormData contents before submit:');
                // for (let pair of formData.entries()) {
                //     console.log(pair[0], pair[1] instanceof File ? pair[1].name : pair[1]);
                // }

                const response = await api.post('/api/users', formData);
                // window.location.href = '/v2/login';
            } catch (error) {
                if (error.response?.data?.errors) {
                    this.errors = error.response.data.errors;
                }
            } finally {
                this.loading = false;
            }
        },

        handleFileUpload(event) {
            const file = event.target.files[0];
            if (file) {
                const fieldName = event.target.name;
                console.log(`File selected for ${fieldName}:`, file);

                // 파일 필드에 따라 처리
                if (fieldName === 'file_user_photo') {
                    this.form.file_user_photo = file;
                    this.photoUrl = URL.createObjectURL(file);
                } else if (fieldName === 'file_user_biz') {
                    this.form.file_user_biz = file;
                    this.fileBizUrl = URL.createObjectURL(file);
                } else if (fieldName === 'file_user_sign') {
                    this.form.file_user_sign = file;
                    this.fileSignUrl = URL.createObjectURL(file);
                } else if (fieldName === 'file_user_cert') {
                    this.form.file_user_cert = file;
                    this.fileCertUrl = URL.createObjectURL(file);
                }
            }
        },

        deletePhotoImg() {
            this.form.file_user_photo = null;
            this.photoUrl = null;
            console.log('After deleting photo:', this.form.file_user_photo);
        },

    }
}
