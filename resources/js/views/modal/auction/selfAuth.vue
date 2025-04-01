<template>
    <button type="button" class="btn border certification" @click="selfAuthModal">본인인증</button>
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
        <div class="sellInfo my-3 p-4 mb-4" style="position: relative; height: 500px; text-align: left;">
            <div class="auction-guid-popup-container">
                <div>
                    <p class="small">* 소유자 인증을 위해 주민번호 & 휴대폰번호는 본인인증을 위해 사용되며, 정보를 수집하지 않습니다.</p>
                </div>
                <div class="mt-3">
                    <p class="mb-2">간편인증</p>
                    <div>
                    <input type="radio" id="loginTypeLevel" class="form-check-input" value="1"> 카카오 
                    <input type="radio" id="loginTypeLevel" class="form-check-input" value="2"> 페이코
                    <input type="radio" id="loginTypeLevel" class="form-check-input" value="3"> 삼성패스
                    <input type="radio" id="loginTypeLevel" class="form-check-input" value="4"> KB모바일
                    <input type="radio" id="loginTypeLevel" class="form-check-input" value="5"> 통신사(PASS)
                    <input type="radio" id="loginTypeLevel" class="form-check-input" value="6"> 네이버
                    <input type="radio" id="loginTypeLevel" class="form-check-input" value="7"> 신한인증서
                    <input type="radio" id="loginTypeLevel" class="form-check-input" value="8"> 토스
                    <input type="radio" id="loginTypeLevel" class="form-check-input" value="9"> 뱅크샐러드
                    </div>
                </div>
                <div class="mt-3">
                    <p class="mb-2">통신사</p>
                    <input type="radio" id="telecom" class="form-check-input" value="0"> SKT(SKT알뜰폰)
                    <input type="radio" id="telecom" class="form-check-input" value="1"> KT(KT알뜰폰)
                    <input type="radio" id="telecom" class="form-check-input" value="2"> LGU+(LGU+알뜰폰)
                </div>
                <div class="mt-3">
                    <p class="mb-2">휴대폰번호</p>
                    <input type="text" id="phoneNo" class="form-control">
                </div>
                <div class="mt-3">
                    <p class="mb-2">소유자 이름</p>
                    <input type="text" id="userName" class="form-control" value="`+props.ownerName+`">
                </div>
                <div class="mt-3">
                    <p class="mb-2">주민번호</p>
                    <input type="text" id="identity" class="form-control">
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

                const loginTypeLevel = document.getElementById('loginTypeLevel').value;
                const telecom = document.getElementById('telecom').value;
                const phoneNo = document.getElementById('phoneNo').value;
                const userName = document.getElementById('userName').value;
                const identity = document.getElementById('identity').value;

                let loginIdentity = identity.substring(0, 6);
                const firstNumber = loginIdentity.substring(0, 1);
                if(firstNumber === '1' || firstNumber === '2' || firstNumber === '3' || firstNumber === '0'){
                    loginIdentity = '20' + loginIdentity;
                }else{
                    loginIdentity = '19' + loginIdentity;
                }

                const data = {
                    loginTypeLevel: loginTypeLevel,
                    telecom: telecom,
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

            }

        })
        .confirm(text); // 모달 내용 설정
    
    };


    const twoWayAuth = (resultData, setData) => {
        
        console.log('setData',setData);

        const text = `
        <h5 class="text-start mb-3">소유자 인증</h5>
        <div class="sellInfo my-3 p-4 mb-4" style="position: relative; height: 500px; text-align: left;">
            <div class="auction-guid-popup-container">
                <div>
                    <p class="small">간편인증 완료후 하단의 확인 버튼을 클릭 해 주세요.</p>
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
                    console.log('resultData',resultData);

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

                        // resIndividualBusinessYN / 개인사업자 여부 확인 / N/Y
                        emit('update:isBusinessOwner', businessStatus.resIndividualBusinessYN);
                        emit('update:isAuth', true);
                        wica.ntcn(swal)
                        .alert('소유자 인증이 완료 되었습니다.');    

                    });

                    // 인증 실패 화면 이동
                    
                }
            })
            .confirm(text, setData); // setData를 두 번째 인자로 전달
    }

    const failAuth = () => {
        alert('인증 실패 화면 이동');
    }

    </script>
    