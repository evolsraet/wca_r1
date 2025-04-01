<template>
    <button type="button" class="btn border certification" @click="selfAuthModal">소유자인증</button>
</template>
    <script setup>
    import { ref, onMounted, computed, watch,inject, defineProps, render } from 'vue';
    import { cmmn } from '@/hooks/cmmn';
    import { useRouter } from 'vue-router';
    import useAuctions from '@/composables/auctions';
    // import imgInfo from'../../../img/auction-detil.png';
    // import imgInfoIcon from'../../../../resources/img/q-mark.png';
    const { wicas , wica , updateAuctionTimes , calculateTimeLeft } = cmmn();

    const { checkBusinessStatus } = useAuctions();

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
        }
    });

    const emit = defineEmits(['update:isBusinessOwner', 'update:isAuth']);

    const isData = ref([]);
       
    const selfAuthModal = () => {

      const text = `
        <h5 class="text-start mb-3">소유자 인증</h5>
        <div class="sellInfo my-3 p-4 mb-4" style="position: relative; height: 700px; text-align: left;">
            <div class="auction-guid-popup-container">

                <div class="form-group mt-1" style="font-size: 17px; background:#f9f9f9; padding: 10px 15px; border-radius: 10px;">
                    <h5 class="small mt-2">개인사업자등록상태 조회 고지사항</h5>
                    <p class="small mt-2">타인의 주민등록번호를 이용한 사업자등록 여부 조회는 <span class="text-danger">개인정보보호법 제 15조 내지 제 24조에 따라 정보주체(주민등록번호 소유자)로 부터 동의를 받은 경우</span>에 가능합니다.</p>
                    <p class="small mt-2">또한 조회대상자의 주민등록번호를 이용하여 사업자등록 여부를 조회한다는 동의를 받아 그 근거를 보관하고 있어야 합니다.</p>
                    <p class="small mt-2">- 위와 같은 동의를 거치지 않고 주민등록번호를 이용하여 사업자등록 여부를 조회하는 것은 <span class="text-danger">개인정보보호법 제 72조에 따라 3년 이하의 징역 또는 3천만원 이하의 벌금</span>에 처할 수 있습니다.</p>
                    <p class="small mt-2">- 정부주체<span class="text-danger">(주민등록번호 소유자)</span>의 손해배상청구 소송의 대상이 될 수 있습니다.</p>
                    <p class="small mt-2 text-danger">* 소유자의 사업자여부 확인을 위해 주민번호 & 휴대폰번호를 사용하며, 정보를 수집하지 않습니다.</p>
                    <div class="form-check mt-3">
                        <input class="form-check-input" type="checkbox" id="agree" name="agree" value="1">
                        <label class="form-check-label" for="agree">위 내용에 <strong>동의합니다.</strong></label>
                    </div>
                </div>

                <div class="form-group mt-3">
                    <label class="mb-2"><span class="text-danger me-2">*</span> 간편인증</label>

                    <div class="form-check">
                        <div class="row">
                            <div class="col-4">
                                <input class="form-check-input" type="radio" id="loginTy1peLevel" name="loginTypeLevel" value="1">
                                <label class="form-check-label" for="loginTypeLevel1"> 카카오 </label>
                            </div>
                            <div class="col-4">
                                <input class="form-check-input" type="radio" id="loginTypeLevel2" name="loginTypeLevel" value="2">
                                <label class="form-check-label" for="loginTypeLevel2"> 페이코 </label>
                            </div>
                            <div class="col-4">
                                <input class="form-check-input" type="radio" id="loginTypeLevel3" name="loginTypeLevel" value="3">
                                <label class="form-check-label" for="loginTypeLevel3"> 삼성패스 </label>
                            </div>
                            <div class="col-4">
                                <input class="form-check-input" type="radio" id="loginTypeLevel4" name="loginTypeLevel" value="4">
                                <label class="form-check-label" for="loginTypeLevel4"> KB모바일 </label>
                            </div>
                            <div class="col-4">
                                <input class="form-check-input" type="radio" id="loginTypeLevel5" name="loginTypeLevel" value="5">
                                <label class="form-check-label" for="loginTypeLevel5"> 통신사(PASS) </label>
                            </div>
                            <div class="col-4">
                                <input class="form-check-input" type="radio" id="loginTypeLevel6" name="loginTypeLevel" value="6">
                                <label class="form-check-label" for="loginTypeLevel6"> 네이버 </label>
                            </div>
                            <div class="col-4">
                                <input class="form-check-input" type="radio" id="loginTypeLevel7" name="loginTypeLevel" value="7">
                                <label class="form-check-label" for="loginTypeLevel7"> 신한인증서 </label>
                            </div>
                            <div class="col-4">
                                <input class="form-check-input" type="radio" id="loginTypeLevel8" name="loginTypeLevel" value="8">
                                <label class="form-check-label" for="loginTypeLevel8"> 토스 </label>
                            </div>
                            <div class="col-4">
                                <input class="form-check-input" type="radio" id="loginTypeLevel9" name="loginTypeLevel" value="9">
                                <label class="form-check-label" for="loginTypeLevel9"> 뱅크샐러드 </label>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="form-group mt-3">
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
                <div class="form-group mt-3">
                    <label class="mb-2"><span class="text-danger me-2">*</span> 휴대폰번호</label>
                    <input type="number" id="phoneNo" class="form-control" placeholder="ex) 01012345678">
                </div>
                <div class="form-group mt-3">
                    <label class="mb-2"><span class="text-danger me-2">*</span> 소유자 이름</label>
                    <input type="text" id="userName" class="form-control" value="`+props.ownerName+`" disabled>
                </div>
                <div class="form-group mt-3">
                    <label class="mb-2"><span class="text-danger me-2">*</span> 주민번호</label>
                    <input type="password" id="identity" class="form-control" placeholder="ex) 9034561234567">
                </div>                

            </div>


            <div class="form-group mt-3 sticky-bottom">
                <button id="customSubmitButton" class="btn btn-primary mt-3 w-100">소유자 인증</button>
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

            const customSubmitButton = document.getElementById('customSubmitButton');

            customSubmitButton.addEventListener('click', (event) => {
                
                // 체크 박스 체크 여부 확인
                const agree = document.getElementById('agree').checked;
                if(agree){

                    const loginTypeLevelElements = document.getElementsByName('loginTypeLevel');
                    const selectedLoginType = Array.from(loginTypeLevelElements).find(radio => radio.checked)?.value;

                    if (!selectedLoginType) {
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

                    const phoneNo = document.getElementById('phoneNo').value;
                    const userName = document.getElementById('userName').value;
                    const identity = document.getElementById('identity').value;

                    if (!phoneNo || !identity) {
                        // wica.ntcn(swal)
                        // .alert('휴대폰번호와 주민번호를 입력해주세요.');
                        alert('휴대폰번호와 주민번호를 입력해주세요.');
                        return;
                    }

                    let loginIdentity = identity.substring(0, 6);
                    const firstNumber = loginIdentity.substring(0, 1);
                    if(firstNumber === '1' || firstNumber === '2' || firstNumber === '3' || firstNumber === '0'){
                        loginIdentity = '20' + loginIdentity;
                    }else{
                        loginIdentity = '19' + loginIdentity;
                    }

                    const data = {
                        loginTypeLevel: selectedLoginType,
                        telecom: selectedTelecom,
                        phoneNo: phoneNo,
                        userName: userName,
                        identity: identity,
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
        <div class="sellInfo my-3 p-4 mb-4" style="position: relative; height: 300px; text-align: left;">
            <div class="auction-guid-popup-container">
                <div>
                    <p class="small">간편인증 완료후 하단의 완료버튼 을 클릭 해 주세요.</p>
                </div>
            </div>
        </div>`;

        wica.ntcn(swal)
            .useHtmlText() // HTML 태그 활성화
            .useClose()
            .addClassNm('primary-check') // 클래스명 설정
            .addOption({ padding: 20 }) // swal 옵션 추가
            .callback(function (result) {
                if(result.isOk){

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
                        twoWayAuth: true
                    }

                    checkBusinessStatus(data).then(result => {
                        console.log('인증결과',result);
                        const businessStatus = result.rawData.data.data;

                        if(businessStatus.resIndividualBusinessYN){
                        // resIndividualBusinessYN / 개인사업자 여부 확인 / N/Y
                            emit('update:isBusinessOwner', businessStatus.resIndividualBusinessYN === 'Y' ? 1 : 0);
                            emit('update:isAuth', true);
                            wica.ntcn(swal)
                            .alert('소유자 인증이 완료 되었습니다.'); 
                        }else{
                            wica.ntcn(swal)
                            .alert('소유자 인증에 실패 하였습니다.'); 
                        }

                    });
                }
            })
            .confirm(text, setData); // setData를 두 번째 인자로 전달

        // setTimeout(() => {
        //     const AuthSubmitButton = document.getElementById('AuthSubmitButton');
        //     AuthSubmitButton.addEventListener('click', (event) => {
                
        //     });
        // }, 1000);

    }

    const failAuth = () => {

        wica.ntcn(swal)
        .alert('필수입력하고 인증을 진행해주세요.');  

    }

    </script>
    