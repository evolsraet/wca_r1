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
                <input v-model="searchTerm" type="text" placeholder="명칭 또는 주소 입력" style="width: auto !important;">
                <button type="button" class="search-btn" @click="searchContacts">검색</button>
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
                                    <span class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">이름</span>
                                </th>
                                <th class="px-6 py-3 text-left">
                                    <div class="flex flex-row justify-content-center">
                                        <div class="font-medium text-uppercase">주소</div>
                                    </div>
                                </th>
                                <th class="px-6 py-3 bg-gray-50 text-left">수정/삭제</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="contact in filteredContacts" :key="contact.id" class="address-row">
                                <td class="px-6 pb-3 pt-1 text-sm">{{ contact.created_at }}</td>
                                <td class="px-6 pb-3 pt-1 text-sm">{{ contact.name }}</td>
                                <td class="px-6 pb-3 pt-1 text-sm text-overflow">{{ contact.addr_post }}, {{ contact.addr1 }}, {{ contact.addr2 }}</td>
                                <td class="px-6 pb-3 pt-1 text-sm">
                                    <router-link :to="{ name: 'addr.update', params: { id: contact.id } }" class="ms-2 badge web_style">
                                        <div class="icon-edit-img"></div>
                                    </router-link>
                                    <a href="#" @click.prevent="deleteContact(contact.id)" class="ms-2 badge web_style">
                                        <div class="icon-trash-img"></div>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                <ul class="pagination justify-content-center">
                    <li class="page-item" :class="{ disabled: currentPage === 1 }">
                    <a class="page-link prev-style" @click="loadPage(currentPage - 1)" :disabled="currentPage === 1"></a>
                    </li>
                    <li v-for="n in pagination.last_page" :key="n" class="page-item" :class="{ active: n === currentPage }">
                    <a class="page-link" @click="loadPage(n)">{{ n }}</a>
                    </li>
                    <li class="page-item next-prev" :class="{ disabled: currentPage === pagination.last_page }">
                    <a class="page-link next-style" @click="loadPage(currentPage + 1)" :disabled="currentPage === pagination.last_page"></a>
                    </li>
                </ul>
            </div>
         </div>
    </div>
    <Footer />
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { useStore } from 'vuex';
import { useRouter } from 'vue-router';
import Footer from "@/views/layout/footer.vue";
import { initAddressBookSystem } from '@/composables/addressbooks';

const currentPage = ref(1);
const { contacts, getContacts, deleteContact, pagination } = initAddressBookSystem();
const searchTerm = ref('');
const store = useStore();
const router = useRouter();

const filteredContacts = computed(() => {
    if (!searchTerm.value) return contacts.value;
    return contacts.value.filter(contact =>
        contact.name.includes(searchTerm.value) || contact.addr1.includes(searchTerm.value) || contact.addr2.includes(searchTerm.value)
    );
});

async function loadPage(page) {
  if (page < 1 || page > pagination.value.last_page) return;
  currentPage.value = page;
  await getContacts(page);
  window.scrollTo(0, 0);
}

const searchContacts = async () => {
  await getContacts();
};

onMounted(() => {
    getContacts();
});
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
