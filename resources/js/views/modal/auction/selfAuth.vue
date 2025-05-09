<template>
    <button type="button" class="btn border certification" @click="selfAuthModal">소유자인증</button>
</template>
    <script setup>
    import { ref, onMounted, computed, watch,inject, defineProps, render } from 'vue';
    import { cmmn } from '@/hooks/cmmn';
    import { useRouter } from 'vue-router';
    import useAuctions from '@/composables/auctions';
    import { useStore } from 'vuex';
    // import imgInfo from'../../../img/auction-detil.png';
    import imgKakao from '../../../../../resources/img/auths/kakao_auth_logo.png';
    import imgPass from '../../../../../resources/img/auths/pass_auth_logo.png';
    import imgPayco from '../../../../../resources/img/auths/payco_auth_logo.png';
    import imgSamsung from '../../../../../resources/img/auths/samsung_auth_logo.png';
    import imgKb from '../../../../../resources/img/auths/kb_auth_logo.png';
    import imgNaver from '../../../../../resources/img/auths/naver_auth_logo.png';
    import imgShinhan from '../../../../../resources/img/auths/sinhan_auth_logo.png';
    import imgToss from '../../../../../resources/img/auths/toss_auth_logo.png';
    import imgBanksalad from '../../../../../resources/img/auths/banksal_auth_logo.png';
    

    const { wicas , wica , updateAuctionTimes , calculateTimeLeft } = cmmn();

    const { checkBusinessStatus } = useAuctions();
    const store = useStore();
    const user = computed(() => store.getters['auth/user']);

    const swal = inject('$swal');
    const router = useRouter();
    const props = defineProps({
        propData: {
            type: Boolean,
            default: false
        },
        ownerName: {
            type: String,
            default: ''
        },
        carNumber: {
            type: String,
            default: ''
        }
    });

    const emit = defineEmits(['update:isBusinessOwner', 'update:isAuth']);

    const isData = ref([]);
       
    const selfAuthModal = () => {

    // todo:소유자 인증 부분 레이아웃 수정 작업 
      const text = `
        <h5 class="text-start mb-3">소유자 인증</h5>
        <div class="sellInfo my-3 p-4 mb-4 auth-modal" style="position: relative; text-align: left; height: 570px;">
            <div class="auction-guid-popup-container">

                <div class="form-group mt-1" id="agreeElement" style="font-size: 17px; background:#f9f9f9; padding: 10px 15px; border-radius: 10px;">
                    <h5 class="small mt-2">개인사업자등록상태 조회 고지사항</h5>
                    <p class="small mt-2">타인의 주민등록번호를 이용한 사업자등록 여부 조회는 <span class="text-danger">개인정보보호법 제 15조 내지 제 24조에 따라 정보주체(주민등록번호 소유자)로 부터 동의를 받은 경우</span>에 가능합니다.</p>
                    <p class="small mt-2">또한 조회대상자의 주민등록번호를 이용하여 사업자등록 여부를 조회한다는 동의를 받아 그 근거를 보관하고 있어야 합니다.</p>
                    <p class="small mt-2">- 위와 같은 동의를 거치지 않고 주민등록번호를 이용하여 사업자등록 여부를 조회하는 것은 <span class="text-danger">개인정보보호법 제 72조에 따라 3년 이하의 징역 또는 3천만원 이하의 벌금</span>에 처할 수 있습니다.</p>
                    <p class="small mt-2">- 정부주체<span class="text-danger">(주민등록번호 소유자)</span>의 손해배상청구 소송의 대상이 될 수 있습니다.</p>
                    <p class="small mt-2 text-danger">* 소유자의 사업자여부 확인을 위해 주민번호 & 휴대폰번호를 사용하며 합니다. 주민등록번호는 이후 명의이전등록, 경락확인서 정보를 위해 보관됩니다.</p>
                    <div class="form-check mt-3" style="margin-top: 10px;">
                        <input class="form-check-input" type="checkbox" id="agree" name="agree" value="1">
                        <label class="form-check-label" for="agree"style="padding-top:5px;">위 내용에 <strong>동의합니다.</strong></label>
                    </div>
                </div>

                <div class="form-group mt-3" id="loginTypeLevel" style="display: none;">
                    <label class="mb-2"><span class="text-danger me-2">*</span> 간편인증</label>

                    <div class="">
                        <div class="row d-flex justify-content-center">
                            <div class="auth_box">
                                <div class="form-check">
                                    <input class="form-check-input visually-hidden" type="radio" id="loginTypeLevel1" name="loginTypeLevel" value="1">
                                    <label class="form-check-label" for="loginTypeLevel1">
                                        <img src="`+imgKakao+`" alt="카카오" class="img-fluid clickable-image">
                                        <p class="text-center">카카오</p>
                                    </label>
                                </div>
                            </div>
                            <div class="auth_box">
                                <div class="form-check">
                                    <input class="form-check-input visually-hidden" type="radio" id="loginTypeLevel2" name="loginTypeLevel" value="2">
                                    <label class="form-check-label" for="loginTypeLevel2">
                                        <img src="`+imgPayco+`" alt="페이코" class="img-fluid clickable-image">
                                        <p class="text-center">페이코</p>
                                    </label>
                                </div>
                            </div>
                            <div class="auth_box">
                                <div class="form-check">
                                    <input class="form-check-input visually-hidden" type="radio" id="loginTypeLevel3" name="loginTypeLevel" value="3">
                                    <label class="form-check-label" for="loginTypeLevel3">
                                        <img src="`+imgSamsung+`" alt="삼성패스" class="img-fluid clickable-image">
                                        <p class="text-center">삼성패스</p>
                                    </label>
                                </div>
                            </div>
                            <div class="auth_box">
                                <div class="form-check">
                                    <input class="form-check-input visually-hidden" type="radio" id="loginTypeLevel4" name="loginTypeLevel" value="4">
                                    <label class="form-check-label" for="loginTypeLevel4">
                                        <img src="`+imgKb+`" alt="KB모바일" class="img-fluid clickable-image">
                                        <p class="text-center">KB모바일</p>
                                    </label>
                                </div>
                            </div>
                            <div class="auth_box">
                                <div class="form-check">
                                    <input class="form-check-input visually-hidden" type="radio" id="loginTypeLevel5" name="loginTypeLevel" value="5">
                                    <label class="form-check-label" for="loginTypeLevel5">
                                        <img src="`+imgPass+`" alt="통신사(PASS)" class="img-fluid clickable-image">    
                                        <p class="text-center">통신사(PASS)</p>
                                    </label>
                                </div>
                            </div>
                            <div class="auth_box">
                                <div class="form-check">
                                    <input class="form-check-input visually-hidden" type="radio" id="loginTypeLevel6" name="loginTypeLevel" value="6">
                                    <label class="form-check-label" for="loginTypeLevel6">
                                        <img src="`+imgNaver+`" alt="네이버" class="img-fluid clickable-image"> 
                                        <p class="text-center">네이버</p>
                                    </label>
                                </div>
                            </div>
                            <div class="auth_box">
                                <div class="form-check">
                                    <input class="form-check-input visually-hidden" type="radio" id="loginTypeLevel7" name="loginTypeLevel" value="7">
                                    <label class="form-check-label" for="loginTypeLevel7">
                                        <img src="`+imgShinhan+`" alt="신한인증서" class="img-fluid clickable-image">   
                                        <p class="text-center">신한인증서</p>
                                    </label>
                                </div>
                            </div>
                            <div class="auth_box">
                                <div class="form-check">
                                    <input class="form-check-input visually-hidden" type="radio" id="loginTypeLevel8" name="loginTypeLevel" value="8">
                                    <label class="form-check-label" for="loginTypeLevel8">
                                        <img src="`+imgToss+`" alt="토스" class="img-fluid clickable-image">
                                        <p class="text-center">토스</p>
                                    </label>
                                </div>
                            </div>
                            <div class="auth_box">
                                <div class="form-check">
                                    <input class="form-check-input visually-hidden" type="radio" id="loginTypeLevel9" name="loginTypeLevel" value="9">
                                    <label class="form-check-label" for="loginTypeLevel9">
                                        <img src="`+imgBanksalad+`" alt="뱅크샐러드" class="img-fluid clickable-image">
                                        <p class="text-center">뱅크샐러드</p>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>


                <div id="auth-form" style="display: none;">

                    <div class="form-group mt-3" id="telecom" >
                        <label class="mb-2"><span class="text-danger me-2">*</span> 통신사</label>
                        <div class="form-check">
                            <div class="row">
                                <div class="col-6">
                                    <input class="form-check-input" type="radio" id="telecom1" name="telecom" value="0">
                                    <label class="form-check-label" for="telecom1"> SKT(SKT알뜰폰) </label>
                                </div>
                                <div class="col-6">
                                    <input class="form-check-input" type="radio" id="telecom2" name="telecom" value="1">
                                    <label class="form-check-label" for="telecom2"> KT(KT알뜰폰) </label>
                                </div>
                                <div class="col-6">
                                    <input class="form-check-input" type="radio" id="telecom3" name="telecom" value="2">
                                    <label class="form-check-label" for="telecom3"> LGU+(LGU+알뜰폰) </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mt-3" id="phoneNoElement" >
                        <label class="mb-2"><span class="text-danger me-2">*</span> 휴대폰번호</label>
                        <input type="number" id="phoneNo" class="form-control" placeholder="ex) 01012345678" value="`+user.value?.phone+`" style="width: 100%; padding: 12px;">
                    </div>
                    <div class="form-group mt-3" id="userNameElement" >
                        <label class="mb-2"><span class="text-danger me-2">*</span> 소유자 이름</label>
                        <input type="text" id="userName" class="form-control" value="`+props.ownerName+`" disabled style="width: 100%;">
                    </div>
                    <div class="form-group mt-3" id="identityElement" >
                        <label class="mb-2"><span class="text-danger me-2">*</span> 주민번호</label>
                        <input type="password" id="identity" class="form-control" placeholder="ex) 9034561234567" style="width: 100%; padding: 12px;">
                    </div>            
                
                </div>

            </div>


            <div class="form-group mt-3 sticky-bottom">
                <button id="customSubmitButton" class="btn btn-primary mt-3 w-100" disabled>소유자 인증</button>
            </div>

        </div>`;
    
      wica.ntcn(swal)
        .useHtmlText() // HTML 태그 활성화
        .useClose()
        .addClassNm('search-event') // 클래스명 설정
        .addOption({ padding: 10 }) // swal 옵션 추가
        .callback(function (result) {

            if(result.isOk){

                

            }

        })
        .confirm(text); // 모달 내용 설정

        setTimeout(() => {
            // 모든 라디오 버튼에 이벤트 리스너 추가
            const loginTypeLevelElements = document.getElementsByName('loginTypeLevel');
            const telecomSection = document.getElementById('telecom');
            const phoneNoSection = document.getElementById('phoneNoElement');
            const userNameSection = document.getElementById('userNameElement');
            const identitySection = document.getElementById('identityElement');
            const loginTypeLevelSection = document.getElementById('loginTypeLevel');
            const agreeButton = document.getElementById('agree');
            const agreeElement = document.getElementById('agreeElement');
            const customSubmitButton = document.getElementById('customSubmitButton');
            const authForm = document.getElementById('auth-form');
            agreeButton.addEventListener('click', (event) => {
                if(agreeButton.value === '1'){
                    loginTypeLevelSection.style.display = 'block';
                    agreeElement.style.display = 'none';
                }else{
                    loginTypeLevelSection.style.display = 'none';
                    agreeElement.style.display = 'block';
                }
            });
            // 각 라디오 버튼에 이벤트 리스너 추가
            loginTypeLevelElements.forEach(radio => {
                radio.addEventListener('change', (event) => {
                    const selectedLoginType = event.target.value;
                    
                    // 통신사 영역 표시/숨김 처리
                    if(selectedLoginType){ // 카카오 선택 시
                        loginTypeLevelSection.style.display = 'none';
                        authForm.style.display = 'block';
                        customSubmitButton.disabled = false;
                    } else {
                        loginTypeLevelSection.style.display = 'block';
                        authForm.style.display = 'none';
                        customSubmitButton.disabled = true;
                    }
                });
            });


            customSubmitButton.addEventListener('click', (event) => {
                
                // 체크 박스 체크 여부 확인
                const agree = document.getElementById('agree').checked;
                if(agree){

                    const loginTypeLevelElementsValue = document.getElementsByName('loginTypeLevel');
                    const selectedLoginTypeValue = Array.from(loginTypeLevelElementsValue).find(radio => radio.checked)?.value;

                    if (!selectedLoginTypeValue) {
                        // wica.ntcn(swal)
                        // .alert('간편인증을 선택해주세요.');
                        alert('간편인증을 선택해주세요.');
                        return;
                    }

                    const telecomElements = document.getElementsByName('telecom');
                    const selectedTelecom = Array.from(telecomElements).find(radio => radio.checked)?.value;

                    if (!selectedTelecom) {
                        // wica.ntcn(swal)
                        // .alert('통신사를 선택해주세요.');
                        alert('통신사를 선택해주세요.');
                        return;
                    }

                    const phoneNoValue = document.getElementById('phoneNo').value;
                    const userNameValue = document.getElementById('userName').value;
                    const identityValue = document.getElementById('identity').value;

                    console.log('phoneNoValue',phoneNoValue);
                    console.log('userNameValue',userNameValue);
                    console.log('identityValue',identityValue);

                    if (!phoneNoValue || !identityValue) {
                        // wica.ntcn(swal)
                        // .alert('휴대폰번호와 주민번호를 입력해주세요.');
                        alert('휴대폰번호와 주민번호를 입력해주세요.');
                        return;
                    }

                    let loginIdentity = identityValue.substring(0, 6);
                    const firstNumber = loginIdentity.substring(0, 1);
                    if(firstNumber === '1' || firstNumber === '2' || firstNumber === '3' || firstNumber === '0'){
                        loginIdentity = '20' + loginIdentity;
                    }else{
                        loginIdentity = '19' + loginIdentity;
                    }

                    const data = {
                        loginTypeLevel: selectedLoginTypeValue,
                        telecom: selectedTelecom,
                        phoneNo: phoneNoValue,
                        userName: userNameValue,
                        identity: identityValue,
                        loginIdentity : loginIdentity,
                        twoWayAuth: false
                    }

                    checkBusinessStatus(data).then(result => {
                        // isData.value = result;
                        const resultData = result.rawData.data.data;
                        const resultCode = result.rawData.data.result;
                        console.log('result',resultData);

                        if(resultData.continue2Way === true){

                            // 2웨이 인증 화면 이동
                            twoWayAuth(resultData, data);

                        }else{

                            // 인증 실패 화면 이동
                            failAuth();
                            
                        }

                    });

                }else{
                    alert('개인사업자등록상태 조회 고지사항에 동의하고 다시 인증해주세요.');
                    // wica.ntcn(swal)
                    // .alert('개인사업자등록상태 조회 고지사항에 동의하고 다시 인증해주세요.');
                    return;
                }

            });

        }, 1000);
    
    };


    const twoWayAuth = (resultData, getData) => {
        
        const resData = resultData;
        const setData = getData;

        const text = `
        <h5 class="text-start mb-3">소유자 인증</h5>
        <div class="sellInfo my-3 p-4 mb-4" style="position: relative; height: 340px; text-align: left;">
            
            <div class="none-info">
                <div class="complete-car">
                    <div class="card my-auction">
                        <div class="none-complete-ty03" style="height: 240px;">
                            <span class="text-secondary text-center fs-5">간편인증 완료후 하단의 인증완료 버튼을 클릭 해 주세요.</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="form-group mt-3 sticky-bottom">
                <button id="submitButton" class="btn btn-primary mt-3 w-100">인증완료</button>
            </div>

        </div>`;

        wica.ntcn(swal)
            .useHtmlText() // HTML 태그 활성화
            .useClose()
            .addClassNm('search-event') // 클래스명 설정
            .addOption({ padding: 20 }) // swal 옵션 추가
            .callback(function (result) {
                // if(result.isOk){
                // }
            })
            .confirm(text, setData); // setData를 두 번째 인자로 전달

            setTimeout(() => {
                const submitButton = document.getElementById('submitButton');
                submitButton.addEventListener('click', (event) => {
                    

                    const data = {
                        loginTypeLevel: setData.loginTypeLevel,
                        telecom: setData.telecom,
                        phoneNo: setData.phoneNo,
                        userName: setData.userName,
                        identity: setData.identity,
                        loginIdentity : setData.loginIdentity,
                        jti: resultData.jti,
                        twoWayTimestamp: resultData.twoWayTimestamp,
                        jobIndex: resultData.jobIndex,
                        threadIndex: resultData.threadIndex,
                        twoWayAuth: true,
                        carNumber: props.carNumber
                    }

                    checkBusinessStatus(data).then(result => {
                        console.log('인증결과',result);
                        const businessStatus = result.rawData.data.data;

                        if(businessStatus.resIndividualBusinessYN){
                        // resIndividualBusinessYN / 개인사업자 여부 확인 / N/Y
                            emit('update:isBusinessOwner', businessStatus.resIndividualBusinessYN === 'Y' ? 1 : 0);
                            emit('update:isAuth', true);
                            emit('update:personal_id_number', setData.identity);
                            wica.ntcn(swal)
                            .alert('소유자 인증이 완료 되었습니다.'); 
                        }else{

                            alert('소유자 인증에 실패 하였습니다.');

                            // wica.ntcn(swal)
                            // .alert('소유자 인증에 실패 하였습니다.'); 
                        }

                    });

                });
            }, 1000);

    }

    const failAuth = () => {

        wica.ntcn(swal)
        .alert('필수입력하고 인증을 진행해주세요.');  

    }

    console.log('user??',user.value);

    </script>
    

    <style>

        .auction-guid-popup-container{
            width: 100% !important;
        }

        .img-fluid{
            width: 50px;
            margin-bottom:10px;
            margin-top:10px;
        }

        .auth_box{
            width: 33%;
            height: 100px;
            padding: 10px;
            text-align: center;
        }

        .visually-hidden {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }

        .clickable-image {
            cursor: pointer;
            transition: opacity 0.3s ease;
        }

        .clickable-image:hover {
            opacity: 0.8;
        }

        .form-check-input:checked + .form-check-label .clickable-image {
            border: 2px solid #d7d7d7;
            border-radius: 10px;
        }

        .form-check-label p{
            font-size: 13px;
        }

        .auth-modal{
            height: calc(100vh - (env(safe-area-inset-bottom) + 450px));
        }


        #auth-form {
            width: 100% !important;
        }

        #loginTypeLevel, #agreeElement, #auth-form {
            height: 450px;
        }

        @media screen and (max-width: 768px) {
            .auth-modal{
                height: calc(100vh - (env(safe-area-inset-bottom) + 250px));
            }
        }

    </style>