<template>
    <div class="container">
        <div class="p-3 row justify-content-center my-2">
            <router-link :to="{ name: 'addr.create' }" class="border-red-write">
                <div class="plus-Icon-wh"></div>
            </router-link>
            <div class="col-md-12"></div>
            <div>
                <h3 class="fw-semibold mt-5">배송지 목록</h3>
                <p>자주 쓰는 배송지를 편리하게 통합관리가 가능합니다.</p>
            </div>
            <div class="search-type2 justify-content-end">
                <input type="text" placeholder="명칭 또는 주소 입력" style="width: auto !important;">
                <button type="button" class="search-btn">검색</button>
            </div>
            <div class="o_table_mobile my-5">
                <div class="tbl_basic tbl_dealer">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 bg-gray-50 justify-content-center">
                                    <div class="flex flex-row items-center justify-content-center justify-between cursor-pointer">
                                        <div class="leading-4 font-medium text-gray-500 uppercase tracking-wider">등록일</div>
                                    </div>
                                </th>
                                <th class="px-6 py-3 bg-gray-50 text-left">
                                    <span class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">지점</span>
                                </th>
                                <th class="px-6 py-3 text-left">
                                    <div class="flex flex-row justify-content-center">
                                        <div class="font-medium text-uppercase">주소</div>
                                    </div>
                                </th>
                                <th class="px-6 py-3 text-left">
                                    <div class="flex flex-row justify-content-center">
                                        <div class="font-medium text-uppercase">메모</div>
                                    </div>
                                </th>
                                <th class="px-6 py-3 bg-gray-50 text-left">수정/삭제</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="address-row">
                                <td class="px-6 pb-3 pt-1 text-sm">등록일</td>
                                <td class="px-6 pb-3 pt-1 text-sm">지점</td>
                                <td class="px-6 pb-3 pt-1 text-sm text-overflow">{{ user.dealer.company_addr1 }} , {{ user.dealer.company_addr2 }}</td>
                                <td class="px-6 pb-3 pt-1 text-sm text-overflow">메모</td>
                                <td class="px-6 pb-3 pt-1 text-sm">
                                 <router-link :to="{ name: 'addr.update' }" class="ms-2 badge web_style">
                                        <div class="icon-edit-img"></div>
                                    </router-link>
                                    <a href="#" class="ms-2 badge web_style">
                                        <div class="icon-trash-img"></div>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <Footer />
</template>

<script setup>
import { ref, computed } from 'vue';
import { useStore } from 'vuex';
import Footer from "@/views/layout/footer.vue"


const store = useStore();

const user = computed(() => store.state.auth.user);

</script>
<style scoped>
.search-type2 .search-btn {
    right: 13px;
    top: 63px !important;
}

.text-overflow {
    max-width: 130px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.address-row td {
    min-width: 200px;
}

@media screen and (max-width: 481px) {
    .web_style {
        margin-left: 0rem !important;
    }
}

@media screen and (max-width: 481px) {
    .o_table_mobile .tbl_basic table {
        width: 100%;
        min-width: 600px !important;
    }
}

.select-option {
    width: 112px !important;
}

@media (max-width: 360px) {
    .responsive-flex-end {
        align-items: flex-end;
        flex-direction: column;
    }
}
</style>
