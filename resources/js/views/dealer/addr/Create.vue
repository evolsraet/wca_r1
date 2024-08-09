<template>
  <div class="container py-4">
    <div class="row justify-content-center my-1">
      <div class="col-md-10">
        <hr>
        <h4 class="pt-2 pb-4">탁송 주소 관리</h4>
        <div class="card border-0 shadow-none">
          <div class="card-body">
            <form @submit.prevent="storeNewContact">
              <div class="mb-3">
                <label for="name" class="form-label">이름</label>
                <input type="text" v-model="contact.name" class="form-control" placeholder=" " />
                <div class="text-danger mt-1" v-if="validationErrors.name">
                  {{ validationErrors.name[0] }}
                </div>
              </div>
              <div class="form-group my-3">
                <label for="addr_post">인수차량 도착지 주소</label>
                <input type="text" v-model="contact.addr_post" class="input-dis form-control" placeholder="우편번호" readonly />
                <button type="button" class="search-btn" @click="editPostCodeReceive('daumPostcodeDealerReceiveInput')">검색</button>
                <div id="daumPostcodeDealerReceiveInput" style="display: none; border: 1px solid; width: 100%; height: 466px; margin: 5px 0px; position: relative">
                  <img src="//t1.daumcdn.net/postcode/resource/images/close.png" style="cursor:pointer;position:absolute;right:0px;top:-1px;z-index:1" @click="closePostcode('daumPostcodeDealerReceiveInput')">
                </div>
                <div class="input-with-button">
                  <input type="text" v-model="contact.addr1" class="input-dis form-control" placeholder="주소" readonly />
                </div>
                <input type="text" v-model="contact.addr2" placeholder="상세주소" class="form-control" />
              </div>
              <div class="my-4">
                <button class="w-100 btn btn-primary" :disabled="isLoading">
                  <span v-if="!isLoading">등록</span>
                  <span v-else>저장 중...</span>
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
import { useRouter } from 'vue-router';
import { useStore } from 'vuex';
import { cmmn } from '@/hooks/cmmn';
import { initAddressBookSystem } from '@/composables/addressbooks';

const router = useRouter();
const swal = inject('$swal');
const store = useStore();
const { openPostcode, closePostcode } = cmmn();

const {
  contact,
  storeContact,
  validationErrors,
  isLoading
} = initAddressBookSystem();

const user = computed(() => store.getters['auth/user']);

onMounted(() => {
  if (user.value && user.value.id) {
    contact.value.user_id = user.value.id;
  }
});

const editPostCodeReceive = (elementName) => {
  openPostcode(elementName)
    .then(({ zonecode, address }) => {
      contact.value.addr_post = zonecode;
      contact.value.addr1 = address;
    });
};

const storeNewContact = async () => {
  try {
    const contactData = {
      name: contact.value.name,
      addr_post: contact.value.addr_post,
      addr1: contact.value.addr1,
      addr2: contact.value.addr2,
      user_id: contact.value.user_id,
    };
    console.log('Submitting Data:', JSON.stringify(contactData, null, 2)); 
    await storeContact(contactData);
    swal({
      icon: "success",
      title: "등록 성공",
      text: "탁송 주소가 성공적으로 등록되었습니다.",
      showConfirmButton: false,
      timer: 3000,
    });
    router.push('/addr');
  } catch (error) {
    swal({
      icon: "error",
      title: "등록 실패",
      text: "탁송 주소 등록에 실패했습니다. 다시 시도해 주세요.",
    });
    if (error.response?.data) {
      validationErrors.value = error.response.data.errors;
      console.log('Validation Errors:', validationErrors.value); 
    }
  }
};
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
