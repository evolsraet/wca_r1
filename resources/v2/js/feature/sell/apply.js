import { appendFormData, appendFilesToFormData, setupFileUploadListeners } from '../../util/fileUpload';

export default function () {
    return {
        form: {
            auction: {
                auction_type: '0',
                owner_name: '',
                car_no: '',
                region: '',
                addr1: '',
                addr2: '',
                bank: '',
                account: '',
                memo: '',
                addr_post: '',
                status: '',
                is_biz: '',
                diag_first_at: '',
                diag_second_at: '',
                car_maker: '',
                car_model: '',
                car_model_sub: '',
                car_grade: '',
                car_grade_sub: '',
                car_year: '',
                car_first_reg_date: '',
                car_mission: '',
                car_fuel: '',
                car_price_now: '',
                car_price_now_whole: '',
                car_thumbnail: '',
                car_km: '',
                car_status: '',
                is_business_owner: '0',
                is_agree: '',
                personal_id_number: '',
                car_engine_type: '',
                car_maker_id: '',
                car_model_id: '',
                car_detail_id: '',
                car_bp_id: '',
                car_grade_id: ''
            },
        },
        checkBusiness: false,
        errors: {},
        loading: false,
        init(initialData = {}) {

            if (typeof initialData === 'object' && initialData !== null && Object.keys(initialData).length > 0) {
                Object.assign(this.form, initialData);
            }

            // 2. window.carInfo 있으면 form.auction에 매핑
            if (window.carInfo && typeof window.carInfo === 'object') {
                Object.entries(window.carInfo).forEach(([key, value]) => {
                    if (this.form.auction.hasOwnProperty(key)) {
                        this.form.auction[key] = value;
                    }
                });
            }

            console.log('form.auction 초기화 결과:', this.form.auction);
            window.apply = this;

            this.dateTimeCheck('auction.diag_first_at'); // 차량 검사 예정일 (2일후 부터 선택 가능)
            this.dateTimeCheck('auction.diag_second_at');

        },
        async submit() {
            this.loading = true;
            this.errors = {};

            try {
                const formData = new FormData();

                // 폼 요소들 가져오기
                const formElements = this.$el.elements;
                appendFormData(formData, formElements);

                const fileFields = [
                    'file_auction_car_license',
                    'file_auction_proxy',
                    'file_auction_company_license'
                ];

                // 본인인증 확인 무조건 확인필요
                // if(!window.resIndividualBusinessYN){
                //     Alpine.store('swal').fire({
                //         title: '본인인증 필요',
                //         text: '본인인증을 해주세요.',
                //         icon: 'error',
                //         confirmButtonText: '확인'
                //     });
                //     return;
                // }

                appendFilesToFormData(formData, fileFields, this.$el);

                if( this.form.isUpdate ) {
                    const response = await Alpine.store('api').put('/api/auctions/' + this.form.auction.id, formData);
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
                    const response = await Alpine.store('api').post('/api/auctions', formData);
                    Alpine.store('swal').fire({
                        title: '경매 등록 완료',
                        text: response.data.message,
                        icon: 'success',
                        confirmButtonText: '확인'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '/v2/auction/'+response.data.data.hashid;
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
        },
        selectedStatus: [], // 체크된 값 배열
        updateStatus() {
            this.form.auction.car_status = this.selectedStatus.join('|');
        },
        checkBusiness() {
            console.log(window.resIndividualBusinessYN);
        },
        dateTimeCheck(el) {
            const input = document.getElementById(el);
            const now = new Date();

            const minDate = new Date(now);
            minDate.setDate(minDate.getDate() + 3);
            minDate.setHours(0, 0, 0, 0); // 자정부터 허용
            minDate.setMinutes(minDate.getMinutes() - minDate.getTimezoneOffset()); // 로컬 타임존 보정

            // 설정
            input.min = minDate.toISOString().slice(0, 16);
        }
    };
}
