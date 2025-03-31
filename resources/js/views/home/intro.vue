<template>
    <div class="pb-5 intocontent">
        <div class="section section01" :class="{ animated: animate, hidden: !animate }">
            <p class="small-text">내차팔때 고민이시죠?</p>
            <p class="title mb-5">부산위카옥션의 내차팔기 서비스</p>
            <div class="chat-box">
                <img src="../../../img/intro-01.png" alt="카카오톡 채팅" class="chat-img">
            </div>
        </div>

        <div class="section section02" :class="{ animated: animate, hidden: !animate }">
            <p class="title mb-5">이제 이런 걱정은 하지 마세요</p>
            <div class="features">
                <div class="feature-item">
                    <img src="../../../img/intro-02.png" alt="손가락 1" class="hand-img mb-22 mt-2">
                    <p>월 1만 대 이상 진단하는<br>진단 전문가의 차량 진단</p>
                </div>
                <div class="feature-item">
                    <img src="../../../img/intro-03.png" alt="손가락 2" class="hand-img mb-22 mt-2">
                    <p>입찰 후 가격 네고없는<br>정직한 시스템</p>
                </div>
                <div class="feature-item">
                    <img src="../../../img/intro-04.png" alt="손가락 3" class="hand-img mb-4 mt-2">
                    <p>믿을 수 있는 nice d&R에서<br>차량 정보 및 시세 제공, 차량<br>대금 관리까지 안전 거래 담보</p>
                </div>
            </div>
        </div>

        <div class="section section03" :class="{ animated: animate, hidden: !animate }">
            <img src="../../../img/intro-06.png" alt="배경" class="bk-section">
            <div class="content">
                <p class="title">내차팔기 이젠 부산위카옥션에 의뢰하세요</p>
                <p>이제 안심하고 내차를 높은 가격으로 판매할 수 있습니다</p>
            </div>
        </div>

        <div class="section section04" :class="{ animated02: animate, hidden: !animate }">
            <img src="../../../img/intro-05.png" width="600px">
            <p class="text-muted02 d-flex mt-4 align-center">
                위카옥션은
                <img src="../../../img/intro-logo-01.png" width="180px" class="ms-2">
                <span class="mx-2">와</span>
                <img src="../../../img/intro-logo-02.png" width="130px" class="me-2">
                이 함께합니다.
            </p>
        </div>
    </div>
    <Footer />
</template>

<script setup>
import { ref, onMounted, nextTick } from "vue";
import Footer from "@/views/layout/footer.vue";

const animate = ref(false);

onMounted(async () => {
    if (!sessionStorage.getItem("animationPlayed")) {
        await nextTick();

        requestAnimationFrame(() => {
            animate.value = true;
        });

        setTimeout(() => {
            sessionStorage.setItem("animationPlayed", "true");
        }, 3000);
    } else {
        animate.value = true;
    }
});
</script>

<style scoped>
.hidden {
    display: none;
}

.animated {
    display: block;
    animation: fadeInUp 1s ease-out;
}
.animated02{
    display: flex;
    animation: fadeInUp 1s ease-out;
}
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
