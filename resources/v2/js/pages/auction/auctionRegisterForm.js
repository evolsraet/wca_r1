import { setupFileUploadListeners, appendFormData, appendFilesToFormData } from '../../util/fileUpload.js';

export default function () {
    return {
        errors: {},
        isLoading: false,
        form: {
            auction: {
                auction_type: '1',
                company_name: '',
                bank: '',
                account: '',
                account_name: '',
            },
            file_auction_excel: null,
            file_auction_car_license: null,
        },
        
        init() {
            console.log('auctionRegisterForm');
            
            // 파일 업로드 이벤트 리스너 설정
            this.fileListeners = setupFileUploadListeners(this.form, this.$el);
        },

        async onSubmit() {
            console.log('onSubmit');
            console.log(this.form.auction);

            console.log('file_auction_excel', this.form.file_auction_excel);
            console.log('file_auction_car_license', this.form.file_auction_car_license);

            try {

                if (!this.form.file_auction_car_license) {
                    Alpine.store('swal').fire({
                        title: '자동차등록증을 업로드해주세요.',
                        icon: 'error',
                        confirmButtonText: '확인'
                    });
                    return;
                }

                if (!this.form.file_auction_excel) {
                    Alpine.store('swal').fire({
                        title: '공매 엑셀파일을 업로드해주세요.',
                        icon: 'error',
                        confirmButtonText: '확인'
                    });
                    return;
                }

                // 로딩 상태 시작
                this.isLoading = true;

                console.log('formData', this.form);

                try {
                    const excelCheckResult = await Alpine.store('auctionEvent').checkAuctionEntryPublic(this.form.file_auction_excel);
                    // console.log('excel check result:', excelCheckResult.data.data);

                    const totalItems = excelCheckResult.data.data.length;
                    let completedItems = 0;
                    let successCount = 0;
                    let errorCount = 0;

                    // 각 아이템을 순차적으로 처리
                    for (const item of excelCheckResult.data.data) {
                        if(item) {
                            try {
                                // console.log('item', item);

                                const setData = {
                                    auction: {
                                      auction_type: '1',
                                      company_name: this.form.auction.company_name,
                                      owner_name: item.orner,
                                      car_no: item.car_no,
                                      bank: this.form.auction.bank,
                                      account: this.form.auction.account,
                                      account_name: this.form.auction.account_name,
                                      region: item.addr1?.split(' ')[0] ?? '',
                                      addr1: item.addr1 || '',
                                      addr2: item.addr2 || '',
                                      addr_post: item.addr_code || '',
                                      hope_price: item.hope_price || '',
                                      customTel1: item.tel || '',
                                      car_maker: item.maker || '',
                                      car_model: item.model || '',
                                      car_model_sub: item.modelSub || '',
                                      car_grade: item.grade || '',
                                      car_grade_sub: item.gradeSub || '',
                                      car_year: item.year || '',
                                      car_first_reg_date: item.firstRegDate || '',
                                      car_mission: item.mission || '',
                                      car_fuel: item.fuel || '',
                                      car_price_now: item.priceNow || '',
                                      car_price_now_whole: item.priceNowWhole || '',
                                      car_thumbnail: item.thumbnail || '',
                                      car_km: item.km || ''
                                    },
                                    file_auction_company_license: this.form.file_auction_car_license
                                  };

                                  console.log('setData', setData);

                                  await Alpine.store('auctionEvent').createAuction(setData);
                                  successCount++;
                            } catch (error) {
                                console.error('개별 공매 등록 오류:', error);
                                errorCount++;
                            }
                        }
                        completedItems++;
                    }

                    // 로딩 상태 종료
                    this.isLoading = false;

                    // 완료 알림
                    if (errorCount === 0) {
                        // 모든 항목이 성공한 경우
                        Alpine.store('swal').fire({
                            title: '공매 등록 완료',
                            text: `공매가 성공적으로 등록되었습니다.`,
                            icon: 'success',
                            confirmButtonText: '확인'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // 페이지 이동 (공매 목록 페이지로 이동)
                                window.location.href = '/v2/auction';
                            }
                        });
                    } else {
                        // 일부 실패한 경우
                        Alpine.store('swal').fire({
                            title: '공매 등록 완료',
                            text: `총 ${totalItems}개 중 ${successCount}개 성공, ${errorCount}개 실패했습니다.`,
                            icon: 'warning',
                            confirmButtonText: '확인'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // 페이지 이동 (공매 목록 페이지로 이동)
                                window.location.href = '/v2/auction';
                            }
                        });
                    }

                    return;
                    
                } catch (error) {
                    // 로딩 상태 종료
                    this.isLoading = false;
                    
                    console.error('엑셀 파일 검증 중 오류:', error);
                    Alpine.store('swal').fire({
                        title: '엑셀 파일 검증 오류',
                        text: '엑셀 파일 검증 중 오류가 발생했습니다.',
                        icon: 'error',
                        confirmButtonText: '확인'
                    });
                }


            } catch (error) {
                // 로딩 상태 종료
                this.isLoading = false;
                
                console.error('Submit error:', error);
                Alpine.store('toastr').error('공매 신청 중 오류가 발생했습니다.');
            }
        },

        showBankSelector() {
            Alpine.store(`modal`).showHtmlFromUrl('/v2/components/modals/bankSelectorModal', {
                id: 'bankSelectorModal',
                title: '은행 선택',
                showFooter: false
            }, {
                result: (result) => {
                    console.log('onResult', result);
                    this.form.auction.bank = result.bank;
                }
            });
        },

        exportExcel() {
            Alpine.store('modal').showHtmlFromUrl('/v2/docs/auctionExcelFormat?raw=1', {
                id: 'auctionExcelFormModal',
                title: '공매 엑셀파일 양식',
                size: 'modal-lg modal-dialog-centered',
                showFooter: false,
            });
        },
    }
}