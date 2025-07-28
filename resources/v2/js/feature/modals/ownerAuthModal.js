export default function () {
    return {
      test: '', // 테스트 모드 여부
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
        
        // 개발환경 감지
        this.test = this.isDevelopment() ? 'test' : '';

        // 초기화 및 감시
        this.$watch('agreed', (val) => {
          if (val) this.step = 2;
          else this.step = 1;
        });
      },
      
      // 개발환경 확인 함수
      isDevelopment() {
        return window.location.hostname === 'localhost' || 
               window.location.hostname === '127.0.0.1' ||
               window.location.hostname.includes('local') ||
               (typeof window.Laravel !== 'undefined' && window.Laravel.env === 'local');
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
        // 개발환경에서는 세션 기반 인증 확인
        if (this.isDevelopment()) {
          return this.developmentAuth();
        }

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
                loginTypeLevel: String(this.selectedAuth),
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
                    window.modalData?.onResult?.({
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
                    window.modalData?.onResult?.({
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
                window.modalData?.onResult?.({
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
            window.modalData?.onResult?.({
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

      // 개발환경용 세션 기반 인증
      async developmentAuth() {
        console.log('🔧 개발환경 인증 시작');
        
        // 세션에서 인증 상태 확인
        const sessionAuth = sessionStorage.getItem('dev_owner_auth');
        const sessionTimestamp = sessionStorage.getItem('dev_owner_auth_timestamp');
        const currentTime = Date.now();
        
        // 세션 유효 시간: 30분
        const sessionValidDuration = 30 * 60 * 1000;
        
        // 기존 세션이 있고 유효한 경우
        if (sessionAuth && sessionTimestamp && 
            (currentTime - parseInt(sessionTimestamp)) < sessionValidDuration) {
          
          console.log('✅ 기존 세션 인증 사용');
          alert('🔧 개발모드: 기존 세션 인증을 사용합니다.');
          
          this.completeDevAuth();
          return;
        }
        
        // 새로운 세션 인증 필요
        console.log('🔑 새로운 세션 인증 필요');
        
        // 개발용 간단한 확인 로직
        const devAuthResult = await this.showDevAuthPrompt();
        
        if (devAuthResult) {
          // 세션에 인증 정보 저장
          sessionStorage.setItem('dev_owner_auth', 'authenticated');
          sessionStorage.setItem('dev_owner_auth_timestamp', currentTime.toString());
          sessionStorage.setItem('dev_owner_auth_user', JSON.stringify({
            name: this.ownerName,
            phone: this.phoneNo,
            carNumber: window.carInfo?.car_no || 'DEV-CAR-001'
          }));
          
          this.completeDevAuth();
        } else {
          console.log('❌ 개발환경 인증 취소');
          Alpine.store('swal').fire({
            title: '개발환경 인증 취소',
            text: '인증이 취소되었습니다.',
            icon: 'info',
            confirmButtonText: '확인'
          });
        }
      },
      
      // 개발환경 인증 완료 처리
      completeDevAuth() {
        window.resIndividualBusinessYN = 'Y';
        window.modalData?.onResult?.({
          resIndividualBusinessYN: 'Y'
        });

        Alpine.store('modal').close('ownerAuthModal');

        console.log('✅ 개발환경 인증 완료');
        Alpine.store('swal').fire({
          title: '🔧 개발모드 인증 성공',
          text: '세션 기반 인증이 완료되었습니다.',
          icon: 'success',
          confirmButtonText: '확인'
        });
      },
      
      // 개발환경용 인증 프롬프트
      async showDevAuthPrompt() {
        return new Promise((resolve) => {
          Alpine.store('swal').fire({
            title: '🔧 개발환경 인증',
            html: `
              <div class="text-start">
                <p><strong>세션 기반 개발 인증</strong></p>
                <ul class="small text-muted">
                  <li>이름: ${this.ownerName}</li>
                  <li>전화번호: ${this.phoneNo}</li>
                  <li>차량번호: ${window.carInfo?.car_no || 'DEV-CAR-001'}</li>
                  <li>세션 유지시간: 30분</li>
                </ul>
                <p class="mt-3">개발환경에서 본인인증을 진행하시겠습니까?</p>
              </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: '인증 진행',
            cancelButtonText: '취소',
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d'
          }).then((result) => {
            resolve(result.isConfirmed);
          });
        });
      },

      closeModal() {
        Alpine.store('modal').close('ownerAuthModal');
      }
    };
  }