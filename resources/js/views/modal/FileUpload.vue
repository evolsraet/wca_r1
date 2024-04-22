<template>
    <section class="modal modal-section type-confirm alert-modal-type02" :style="{ display: showModal ? 'block' : 'none' }">
        <div class="modal-dialog-ty03">
            <div class="modal-content shadow">
                <div class="modal-body-ty02" :class="{ 'no-scroll': isActive && showDetails }" ref="modalBody">

                    <div class="content">
                        <div class="nav-header">
                            <button type="button" class="btn-close" @click="handleCloseClick"></button>
                        </div>
                        <h5>추가 정보를 입력해주세요</h5>
                        <p class="tc-light-gray mt-5">사진 (본인 확인용)</p>
                        <div class="file-upload">
                            <div class="image-file">
                                <div v-if="singleImage" class="image-preview">
                                    <div class="image-container">
                                    <img :src="singleImage" alt="Uploaded image">
                                    <button class="delete-button" @click="removeSingleImage">x</button>
                                </div>
                            </div>
                            </div>
                            <button class="btn btn-fileupload mt-3 mb-5" @click="triggerSingleFileUpload">단일 파일 첨부</button>
                            <input type="file" accept="image/*" @change="handleSingleFileChange" ref="singleFileInput" style="display: none;">
                        </div>

                    
                        <h5><p>본인 소유 차량이 아닐 경우,</p>위임장 또는 소유자 인감 증명서가 필요해요</h5>
                        <div class="file-upload mt-4">
                        <div class="image-file">
                            <div class="image-preview" v-for="(image, index) in documentImages" :key="index">
                                <div class="image-container">
                                    <img :src="image.url" :alt="'Document image ' + index">
                                    <button class="delete-button" @click="removeDocumentImage(index)">X</button>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-fileupload mt-3 mb-5" @click="triggerDocumentUpload">위임장 파일 첨부</button>
                        <input type="file" multiple accept="image/*" @change="handleDocumentImagesChange" ref="documentInput" style="display: none;" />
                    </div>
                        <h5>추가 서류가 필요해요</h5>
                        <ul class="mt-4 mb-4">
                            <li>사업자등록증</li>
                            <li>매도용 인감정보</li>
                            <li>매매업체 대표증</li>
                            <li>종사원증</li>
                        </ul>
                        <div class="file-upload mt-4 mb-5">
                        <div class="image-file">
                            <div class="image-preview" v-for="(image, index) in additionalImages" :key="index">
                                <div class="image-container">
                                    <img :src="image.url" :alt="'Additional image ' + index">
                                    <button class="delete-button" @click="removeAdditionalImage(index)">X</button>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-fileupload mt-3" @click="triggerAdditionalUpload">추가 서류 파일 첨부</button>
                        <input type="file" multiple accept="image/*" @change="handleAdditionalImagesChange" ref="additionalInput" style="display: none;" />
                        <router-link :to="{ name: 'autction.index' }" class="link process my-member justify-content-end">정보갱신하기</router-link>
                    </div>
                        <h5>경매 시 사용할 계좌가 필요해요</h5>
                        <div class="form-group mt-4">
                            <label for="carNumber">은행</label>
                            <input type="text" id="bank" placeholder="은행 선택" @click="handleBankLabelClick" v-model="selectedBank" readonly>
                        </div>
                        <div class="show-content" :class="{ 'active': showDetails }">
                            <div class="detail-content02 mt-5 p-0" :class="{ 'active': isActive }" @click="toggleDetailContent">
                            <h3 class="title">은행 선택</h3>
                            <div class="nav-header">
                            <button type="button" class="btn-close p-3" @click.stop="closeDetailContent"></button>
                            </div>
                            <div class="content mt-0 p-0" @click.stop>
                                <ul class="menu">
                                <li class="menu-item active">은행</li>
                                <li class="menu-item">증권</li>
                            </ul>
                            <div class="grid bank-selection">
                                <div class="bank-item" data-bank="하나" @click="selectBank('하나')">하나</div>
                                <div class="bank-item" data-bank="국민" @click="selectBank('국민')">국민</div>
                                <div class="bank-item" data-bank="우리" @click="selectBank('우리')">우리</div>
                                <div class="bank-item" data-bank="신한" @click="selectBank('신한')">신한</div>
                                <div class="bank-item" data-bank="기업" @click="selectBank('기업')">기업</div>
                                <div class="bank-item" data-bank="대구" @click="selectBank('대구')">대구</div>
                            </div>
                        </div>
                    </div>
                </div>


                    <div class="flex items-center justify-end my-5">
                    <router-link :to="{ path: '/selldt' }" class="btn primary-btn normal-16-font mt-5">경매 신청하기</router-link></div>
                </div>
                </div>
            </div>
        </div>
    </section>
</template>
<script setup>
import { ref, nextTick } from 'vue';
const showDetails = ref(false);
const isActive = ref(false);
const showModal = ref(true);
const modalBody = ref(null);
const selectedBank = ref('');

function handleCloseClick() {
    showModal.value = false;
}

function handleBankLabelClick() {
    showDetails.value = true;
}
function selectBank(bankName) {
    selectedBank.value = bankName; 
    console.log(bankName + ' 선택됨');
}

function closeDetailContent() {
    showDetails.value = false;
    isActive.value = false;  

}

function toggleDetailContent() {
    if (!isActive.value) {
        // 스크롤을 최상단으로 이동
        if (modalBody.value) {
            modalBody.value.scrollTop = 0; // 스크롤 최상단으로 이동
        }
        // 스크롤 이동 후 isActive를 변경하여 스크롤 제한 적용
        nextTick(() => {
            isActive.value = true;
        });
    } else {
        // 상태를 먼저 변경한 후 스크롤 잠금 해제
        isActive.value = false; 
    }
}
</script>


<script>
export default {
    data() {
        return {
            showModal: true,
            documentImages: [], // 위임장, 소유자 인감 증명서 이미지
            additionalImages: [], // 추가 서류 이미지
            singleImage: null // 단일 이미지 저장
        };
    },
    methods: {
    handleCloseClick() {
        this.showModal = false;
        this.$emit('close');
    },
    handleDocumentImagesChange(event) {
            const files = event.target.files;
            for (let i = 0; i < files.length; i++) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.documentImages.push({ url: e.target.result });
                };
                reader.readAsDataURL(files[i]);
            }
        },
        handleAdditionalImagesChange(event) {
            const files = event.target.files;
            for (let i = 0; i < files.length; i++) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.additionalImages.push({ url: e.target.result });
                };
                reader.readAsDataURL(files[i]);
            }
        },
    removeDocumentImage(index) {
            this.documentImages.splice(index, 1);
        },
        triggerDocumentUpload() {
            this.$refs.documentInput.click();
        },
        removeAdditionalImage(index) {
            this.additionalImages.splice(index, 1);
        },
        triggerAdditionalUpload() {
            this.$refs.additionalInput.click();
        },
    handleSingleFileChange(event) {
        const file = event.target.files[0]; // 파일 하나만 선택
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                this.singleImage = e.target.result; // 단일 이미지 데이터 업데이트
            };
            reader.readAsDataURL(file);
        }
    },
    removeSingleImage() {
        this.singleImage = null; // 단일 이미지 삭제
    },
    triggerSingleFileUpload() {
            this.$refs.singleFileInput.click(); 
        },
}

}
</script>
<style>





.show-content {
    visibility: hidden; /* 초기 상태는 보이지 않음 */
    opacity: 0;         /* 투명하게 설정 */
    transition: visibility 0s linear 0.5s, opacity 0.5s linear; /* transition을 적용 */
}

.show-content.active {
    visibility: visible; /* 활성 상태에서는 보이게 */
    opacity: 1;           /* 불투명하게 */
    transition: opacity 0.5s linear; /* transition을 적용 */
}

.tooltip-toggle {
    cursor: pointer;
    position: relative;
    width: 20px;
    height: 20px;
    background-image: url('../../../../img/tooltip-toggle.png');
    background-repeat: no-repeat;
    background-size: contain;
    background-position: center;
    display: inline-block;
    margin-left: 10px;
    vertical-align: text-bottom;



  &::before,
  &::after {
    position: absolute;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.5s ease;
  }

  &::before {
    content: attr(aria-label);
    top: -77px;
    left: 80px;
    transform: translateX(-50%);
    background-color: #fffcd4;
    color: #ffcb00;
    text-align: center;
    border-radius: 6px;
    border: 1px solid #ffcb00;
    padding: 8px;
    width: auto;
    min-width: 210px;
}

  &::after {
    content: '';
    top: -16px;
    left: 50%;
    transform: translateX(-50%);
    border-left: 6px solid transparent;
    border-right: 6px solid transparent;
    border-top: 8px solid #fee585;
  }

  &:hover::before,
  &:hover::after,
  &:focus::before,
  &:focus::after {
    opacity: 1; 
  }
}

.no-scroll {
    overflow: hidden; 
}


.link{
    text-decoration : underline !important;
}
.none-complete-ty02 span::before {
    content: "";
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 91px;
    height: 91px;
    background-image: url('../../../../img/electric-car.png');
    background-size: contain;
    background-repeat: no-repeat;
    z-index: 1;
}
.none-complete-ty02 {
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    height: 160px;
    background-color: #f5f5f6;
}
.detail-content02.active {
    bottom: 0px;
}
.middle{
    display: flex;
    margin-top: 35px;
    justify-content: space-between;
}
.detail-content02 .top-content{
    display: flex;
    margin-top: 35px;
    height: 50px;
    background-color: #ebedf1;
    border-radius: 6px;
    justify-content: space-between;
    padding: 13px;
}

.detail-content02 {
    background-color: #f8f9fa;
    box-shadow: 0px -7px 16px 0px rgba(212, 212, 212, 0.53);
    border-top-right-radius: 30px;
    border-top-left-radius: 30px;
    position: absolute;
    width: 100%;
    left: 0;
    transition: top 0.5s ease;
    padding: 5px 20px;
    box-sizing: border-box;
    top: auto;
}

@media (max-width: 400px) {
    .detail-content02.active {
    top: calc(640px - 540px);
    height: auto;
}
}


@media (min-width: 401px) and (max-width: 768px) {
    .detail-content02 {
        top: auto;
    }
    
    
    .detail-content02.active {
        top: calc(640px - 530px);
        height: auto;
    }
}

@media (min-width: 769px) and (max-width: 1024px) {
    .detail-content02 {
        top: auto;
    height: -webkit-fill-available;
}
    .detail-content02.active {
        top: calc(600px - 250px);
        height: auto;
    }
}

@media (min-width: 1025px) {

    .detail-content02.active {
        top: calc(600px - 250px);
        height: auto;
    }
}


.machine-inform-title {
    list-style: none; 
    padding: 0; 
    margin-top: 30px;
    display: flex;
    justify-content: flex-start;
}
.machine-inform {
    list-style: none; 
    padding: 0; 
    margin-top: 10px;
    display: flex;
    justify-content: flex-start;
}
.sub-title {
    margin-right: 20px;
}
.machine-inform-title li, .machine-inform li {
    flex: 1;
}

.machine-inform-title li.car-icon{
    flex: 0 0 20px;
    height: 20px;
    background-image: url('../../../../img/clean.png');
    background-repeat: no-repeat;
    background-size: contain;
    background-position: center;
    background-color: #fff;
    margin-left: auto; 
}

.machine-inform-title li.info-num, .machine-inform li.sub-title {
    flex: 1.5; 
}




.btn-fileupload::before{
    content: '';
    width: 13px;
    height: 20px;
    background-image: url('../../../img/Icon-upload.png');
    margin-right: 8px;
    background-repeat: no-repeat; 
    background-size: contain;
    background-position: center; 
    display: inline-block;
    vertical-align: text-bottom;

}
.image-file {
    display: flex;
    width: 100%;
    overflow-x: auto;
    flex-direction: row;
    flex-wrap: nowrap;
    gap: 10px;
}

.file-upload {
    display: flex;
    flex-direction: column; 
    overflow: hidden; 
}
.image-container {
    width: 100px;
}

.image-preview {
    display: flex;
    flex-wrap: nowrap;
    align-items: center; 
    padding: 10px 0; 
}

.image-preview .image-container {
    flex-shrink: 0;
    position: relative;
    margin-right: 40px;
    margin-bottom: 10px;
    width: 200px;
    height: 150px;
    overflow: hidden;
    border-radius: 20px;
}

.image-preview img {
    width: 100px;
    height: auto;
    display: block;
    object-fit: fill;
    width: 100%;
    height: 100%;
}


.modal-dialog-ty02 {
    height: 100%;
    width: 100%;
    margin: 0px !important;
    padding: 0px !important;
}


.image-preview .delete-button {
    position: absolute;
    top: 8px;
    right: 11px;
    padding: 2px 5px;
    background-color: white;
    border-radius: 50%;
    width: 25px;
    height: 25px;
    color: black;
    border: none;
    cursor: pointer;
}
</style>

