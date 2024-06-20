<template>
    <div class="container mov-wide">
        <div class="d-flex flex-column">
        <div class="mt-3">
            <p class="mx-2 my-2 top-title">이런 단계를 거쳐요</p>
        </div>
        <div>
            <div class="introduce-action">
            <img src="../../../../img/auction-detil.png" alt="Auction Detail" class="auction-detail-img">
        </div>
        <div class="mb-4 p-5 flex items-center justify-end container justify-content-center">
            <router-link :to="{ path: '/selldt2' }" class="btn primary-btn btn-style">다음</router-link>
        </div>
        </div>
    </div>
</div>
</template>

<script>

</script>
<style>
.btn-style{
    width: 422px;
}
</style>
