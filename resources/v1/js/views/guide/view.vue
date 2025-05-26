<template>
   <div class="container row my-2 mov-wide m-auto mb-5">
        <div class="mb-4">
            <p class="mt-5 mb-1">서류 준비에 참고하세요</p>
            <h3>명의이전서류 안내</h3>
        </div>
        <div class="d-flex gap-3 px-3">
            <div class="guide-box">
                <p class="title mb-2">일반고객</p>
                <div class="sub">
                    <ol>
                        <li class="mb-1">자동차 등록증 원본</li>
                        <li class="mb-1">자동차매도용 인감증명서 또는 본인 서명 사실 확인서 (매수자 인적사항 기재 필수)</li>
                        <ul>
                            <li>공동명의인 경우 명의자 각자 서류 필요</li>
                            <li>수출 딜러에게 판매될 경우 인감증명서 대신 신분증 사진 필요</li>
                        </ul>
                    </ol>
                </div>
            </div>
            <div class="guide-box">
                <p class="title mb-2">간이과세자(세금계산서 발급사업자)</p>
                <ol>
                    <li class="mb-1">자동차 등록증 원본</li>
                    <li class="mb-1">자동차매도용 인감증명서(매수자 인적사항 기재 필수)</li>
                    <li class="mb-1">사업자등록증 사본</li>
                </ol>
            </div>
        </div>
        <div class="px-3 mt-3">
            <div class="guide-box relatevie-box">
                <p class="title">일반과세자, 면세사업자</p>
                <p class="sub-title mb-1">사업용으로 사용한 경우</p>
                <ol class="px-4">
                    <li class="mb-1">자동차 등록증 원본</li>
                    <li class="mb-1">자동차매도용 인감증명서(매수자 인적사항 기재 필수)</li>
                    <li class="mb-1">사업자등록증 사본</li>
                    <li class="mb-1">세금계산서 (부가세 면제)</li>
                </ol>
                <div class="mt-3">
                    <p class="sub-title mb-2">사업용으로 사용하지 않은 경우</p>
                    <p class="px-2">복식부기</p>
                    <ol class="px-4">
                        <li class="mb-1">자동차등록증 원본</li>
                        <li class="mb-1">자동차매도용 인감증명서(매수자 인적사항 기재 필수)</li>
                        <li class="mb-1">일반 인감증명서 (사실확인 증빙용)</li>
                        <li class="mb-1">사업자등록증 사본</li>
                        <li class="mb-1">비사업용 사실확인서 (세무사 명판 날인)</li>
                        <li class="mb-1">'차량운반구 감가상각비명세서' 또는 '고정자산명세서'</li>
                    </ol>
                </div>
                <div class="mt-3">
                    <p class="px-2">간편장부</p>
                    <ol class="px-4">
                        <li class="mb-1">자동차등록증 원본</li>
                        <li class="mb-1">자동차매도용 인감증명서(매수자 인적사항 기재 필수)</li>
                        <li class="mb-1">일반 인감증명서 (사실확인 증빙용)</li>
                        <li class="mb-1">사업자등록증 사본</li>
                        <li class="mb-1">비사업용 사실확인서 (세무사 명판 날인)</li>
                        <li class="mb-1">총 수입금액 및 필요경비명세서</li>
                    </ol>
                </div>
                    <img src="../../../img/transferGuide.png" width="200px" class="image-top">
            </div>
            <div class="guide-box mt-3">
                <p class="title mb-2">법인</p>
                <ol>
                    <li class="mb-1">자동차 등록증 원본</li>
                    <li class="mb-1">자동차매도용 인감증명서 (매수자 인적사항 기재 필수)</li>
                    <li class="mb-1">사업자등록증 사본</li>
                    <li class="mb-1">세금계산서 (부가세 면제)</li>
                    <li class="mb-1">법인등기부등본(15일내에 발급, 말소사항 포함)</li>
                    <li class="mb-1">양도계약서</li>
                </ol>
            </div>
        </div>
        
   </div>
</template>
<style scoped>
h3{font-weight: bold;}
.guide-box{background-color: #F7F8FB; border-radius: 12px; width: 100%; padding: 30px;}
.guide-box .title{font-size: 18px; font-weight: bold;}
.guide-box ol{padding-left: 0.8rem; font-size: 16px;}
.guide-box ul{list-style-type: initial !important; font-size: 16px;}
.guide-box ul{padding-left: 1rem;}
.guide-box .sub{font-size: 16px;}
.guide-box .sub-title{font-weight: bolder;}
.relatevie-box {position: relative;padding-top: 80px; }
.image-top {position: absolute;top: 515px;right: -41px;transform: translateX(-50%);width: 200px;}
</style>