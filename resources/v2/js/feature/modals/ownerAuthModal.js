export default function () {
    return {
      test: 'test', // 테스트 모드 여부
      step: 1,
      agreed: false,
      selectedAuth: null,
      phoneNo: '',
      identity: '',
      ownerName: '홍길동',
      telecom: '',
      setData: {},
      authResultData: null,
      init() {
        this.ownerName = window.carInfo.owner_name;
        this.phoneNo = window.user_phone;

        // 초기화 및 감시
        this.$watch('agreed', (val) => {
          if (val) this.step = 2;
          else this.step = 1;
        });
      },
  
      selectAuth(authType) {
        this.selectedAuth = authType;
        this.step = 3;
      },

      authMethods: [
        { name: '카카오', type: 'kakao', img: '/images/auths/kakao_auth_logo.png' },
        { name: '페이코', type: 'payco', img: '/images/auths/payco_auth_logo.png' },
        { name: '삼성패스', type: 'samsung', img: '/images/auths/samsung_auth_logo.png' },
        { name: 'KB모바일', type: 'kb', img: '/images/auths/kb_auth_logo.png' },
        { name: '통신사', type: 'pass', img: '/images/auths/pass_auth_logo.png' },
        { name: '네이버', type: 'naver', img: '/images/auths/naver_auth_logo.png' },
        { name: '신한인증서', type: 'sinhan', img: '/images/auths/sinhan_auth_logo.png' },
        { name: '토스', type: 'toss', img: '/images/auths/toss_auth_logo.png' },
        { name: '뱅크샐러드', type: 'banksal', img: '/images/auths/banksal_auth_logo.png' },
      ],
      telecoms: {
        0: 'SKT',
        1: 'KT',
        2: 'LGU+'
      },
      async submit() {

        if(!this.telecom) {

            Alpine.store('swal').fire({
                title: '통신사를 선택해주세요.',
                icon: 'error',
                confirmButtonText: '확인'
            });

            return;
        }

        if(!this.phoneNo) {

            document.getElementById('phoneNo').focus();

            Alpine.store('swal').fire({
                title: '휴대폰번호를 입력해주세요.',
                icon: 'error',
                confirmButtonText: '확인'
            });

            return;
        }

        if(!this.identity) {

            document.getElementById('identity').focus();
            
            Alpine.store('swal').fire({
                title: '주민번호를 입력해주세요.',
                icon: 'error',
                confirmButtonText: '확인'
            });

            return;
        }

        // api 통신후 1차 인증이 되면 현재 모달창은 닫고, 인증완료 모달창 띄움 
        if(this.agreed) {

            let loginIdentity = this.identity.substring(0, 6);
            const firstNumber = loginIdentity.substring(0, 1);
            if(firstNumber === '1' || firstNumber === '2' || firstNumber === '3' || firstNumber === '0'){
                loginIdentity = '20' + loginIdentity;
            }else{
                loginIdentity = '19' + loginIdentity;
            }

            const formData = {
                loginTypeLevel: this.selectedAuth,
                telecom: this.telecom,
                phoneNo: this.phoneNo,
                userName: this.ownerName,
                identity: this.identity,
                loginIdentity : loginIdentity,
                twoWayAuth: false
            }

            this.setData = formData;

            if(!this.test === 'test'){
                await this.$store.api.post('/api/check-business', formData)
                .then(result => {
                    const resultData = result.rawData.data.data;

                    if(resultData.continue2Way === true){
                        // 2웨이 인증 화면 이동
                        this.step = 4;
                        this.authResultData = resultData;
                    }else{
                        // 인증 실패 화면 이동
                        this.step = 5;
                    }
                })
                .catch(error => {
                    console.log(error);
                    this.step = 5;
                });
            }else{
                this.step = 4;
                this.authResultData = {
                    jti: '1234567890',
                    twoWayTimestamp: '2025-01-01 12:00:00',
                    jobIndex: 1,
                    threadIndex: 1
                };
            }

        }
      },

      async authSubmit() {
        console.log('authSubmit');

        console.log(this.authResultData);

        const submitData = {
            loginTypeLevel: this.setData.loginTypeLevel,
            telecom: this.setData.telecom,
            phoneNo: this.setData.phoneNo,
            userName: this.setData.userName,
            identity: this.setData.identity,
            loginIdentity : this.setData.loginIdentity,
            jti: this.authResultData.jti,
            twoWayTimestamp: this.authResultData.twoWayTimestamp,
            jobIndex: this.authResultData.jobIndex,
            threadIndex: this.authResultData.threadIndex,
            twoWayAuth: true,
            carNumber: window.carInfo.car_no
        }

        if(!this.test === 'test'){
        await this.$store.api.post('/api/check-business', submitData)
            .then(result => {
                const businessStatus = result.rawData.data.data;

                console.log(businessStatus);

                if(businessStatus.resIndividualBusinessYN){

                    window.resIndividualBusinessYN = 'Y';
                    window.modalOptions?.onResult?.({
                        resIndividualBusinessYN: 'Y'
                    });

                    Alpine.store('modal').close('ownerAuthModal');

                    Alpine.store('swal').fire({
                        title: '인증에 성공 하였습니다.',
                        icon: 'success',
                        confirmButtonText: '확인'
                    });

                }else{
                    window.resIndividualBusinessYN = 'N';
                    window.modalOptions?.onResult?.({
                        resIndividualBusinessYN: 'N'
                    });

                    Alpine.store('swal').fire({
                        title: '인증에 실패 하였습니다.',
                        icon: 'error',
                        confirmButtonText: '확인'
                    });

                }

            })
            .catch(error => {
                window.resIndividualBusinessYN = 'N';
                window.modalOptions?.onResult?.({
                    resIndividualBusinessYN: 'N'
                });
                
                Alpine.store('swal').fire({
                    title: '인증에 실패 하였습니다.',
                    icon: 'error',
                    confirmButtonText: '확인'
                });
            });
        }else{
            window.resIndividualBusinessYN = 'Y';
            window.modalOptions?.onResult?.({
                resIndividualBusinessYN: 'Y'
            });

            Alpine.store('modal').close('ownerAuthModal');

            Alpine.store('swal').fire({
                title: '인증에 성공 하였습니다.',
                icon: 'success',
                confirmButtonText: '확인'
            });
        }
      },

      closeModal() {
        Alpine.store('modal').close('ownerAuthModal');
      }
    };
  }