<template>
  <div class="container py-4">
    <div class="row justify-content-center my-1">
      <div class="col-md-10">
        <hr>
        <h4 class="pt-2 pb-4">탁송 주소 관리</h4>
        <div class="card border-0 shadow-none my-4">
          <div class="card-body">
            <form @submit.prevent="submitForm">
              <div class="mb-3">
                <label for="user-title" class="form-label">명칭</label>
                <input type="text" class="form-control" placeholder="예 ) 대전점" v-model="contact.name" />
                <div class="text-danger mt-1">
                  <span v-if="validationErrors.name">{{ validationErrors.name[0] }}</span>
                </div>
              </div>
              <div class="form-group my-3">
                <label for="dealer">인수차량 도착지 주소</label>
                <input type="text" v-model="contact.addr_post" class="input-dis form-control" readonly />
                <button type="button" class="search-btn" @click="editPostCodeReceive('daumPostcodeDealerReceiveInput')">검색</button>
                <div id="daumPostcodeDealerReceiveInput" style="display: none; border: 1px solid; width: 100%; height: 466px; margin: 5px 0px; position: relative">
                  <img src="//t1.daumcdn.net/postcode/resource/images/close.png" style="cursor:pointer;position:absolute;right:0px;top:-1px;z-index:1" @click="closePostcode('daumPostcodeDealerReceiveInput')">
                </div>
                <div class="input-with-button">
                  <input type="text" v-model="contact.addr1" class="input-dis form-control" readonly />
                </div>
                <input type="text" v-model="contact.addr2" class="form-control" />
              </div>
              <div class="my-4" v-if="!navigatedThroughHandleRowClick">
                <button class="w-100 btn btn-primary">
                  <span>등록</span>
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <Footer />
</template>

<script setup>
import Footer from "@/views/layout/footer.vue";
import { ref, onMounted, inject, computed } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { useStore } from 'vuex';
import useUsers from "@/composables/users";
import { cmmn } from '@/hooks/cmmn';
import { initAddressBookSystem } from '@/composables/addressbooks';
const store = useStore();
const user = computed(() => store.getters['auth/user']);
const { getUser } = useUsers();
const { getContact, updateContact, contact } = initAddressBookSystem();

const router = useRouter();
const route = useRoute();
const navigatedThroughHandleRowClick = ref(route.query.navigatedThroughHandleRowClick === 'true');
const swal = inject('$swal');
const contactId = ref(route.params.id); // 현재 페이지의 id 값을 가져옴
const validationErrors = ref({});
const { openPostcode, closePostcode } = cmmn();

function editPostCodeReceive(elementName) {
  openPostcode(elementName)
    .then(({ zonecode, address }) => {
      contact.value.addr_post = zonecode;
      contact.value.addr1 = address;
      console.log('Updated Contact Data:', contact.value);
    });
}

const submitForm = async () => {
  const contactData = {
    name: contact.value.name,
    addr_post: contact.value.addr_post,
    addr1: contact.value.addr1,
    addr2: contact.value.addr2,
    user_id: user.value.id, 
  };
  console.log('Submitting Data:', contactData); 
  try {
    const response = await updateContact(contactId.value, contactData);
    if (response.status === 'ok' || response.code === 200) {
      console.log('Updated Data from Server:', response.data);
      swal({
        icon: "success",
        title: "주소가 성공적으로 업데이트되었습니다.",
        showConfirmButton: false,
        timer: 3000,
      });
      router.push({ name: 'dealer.address' });
    } else {
      console.error('Update failed:', response);
    }
  } catch (error) {
    console.error('Update Error:', error);
    if (error.response?.data) {
      validationErrors.value = error.response.data.errors;
    }
  }
};

onMounted(async () => {
  if (user.value && user.value.id) {
    contact.value.user_id = user.value.id;
    await getContact(contactId.value);
  }
});
</script>

<style scoped>
h4 {
  position: relative;
  padding-bottom: 15px;
}

h4::after {
  content: "";
  display: block;
  width: 100%;
  height: 2px;
  background-color: #f7f8fb;
  position: absolute;
  bottom: 0;
  left: 0;
}
.search-btn{
  transform: translateY(-125%) !important;
}
</style>
