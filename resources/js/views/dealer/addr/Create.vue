<template>
    <div class="container py-4">
        <div class="row justify-content-center my-1">
            <div class="col-md-10">
            <hr>
            <h4 class="pt-2 pb-4">탁송 주소 관리</h4>
            <div class="card border-0 shadow-none">
                <div class="card-body">
                    <form>
                        <div class="mb-3">
                            <label for="user-title" class="form-label">명칭</label>
                            <input type="text" class="form-control" placeholder="예 ) 대전점" />
                            <div class="text-danger mt-1">
                                <!-- Error messages -->
                            </div>
                            <div class="form-group my-3">
                            <label for="dealer">인수차량 도착지 주소</label>
                            <input type="text" v-model="profile.receive_post" class="input-dis form-control" readonly />
                            <button type="button" class="search-btn" @click="editPostCodeReceive('daumPostcodeDealerReceiveInput')">검색</button>
                            <div class="input-with-button">
                                <input type="text" v-model="profile.receive_addr1" class="input-dis form-control" readonly />
                            </div>
                            <input type="text" v-model="profile.receive_addr2" class="form-control" />
                            <div id="daumPostcodeDealerReceiveInput" style="display: none; border: 1px solid; width: 100%; height: 466px; margin: 5px 0px; position: relative">
                                <img src="//t1.daumcdn.net/postcode/resource/images/close.png" style="cursor:pointer;position:absolute;right:0px;top:-1px;z-index:1" @click="closePostcode('daumPostcodeDealerReceiveInput')">
                            </div>
                            </div>
                            <div class="my-2">
                                <label for="user-title" class="form-label">메모</label>
                                <textarea class="form-control no-resize mt-2" style="height: 100px; overflow-y: hidden;" placeholder="메모 입력"></textarea>
                            </div>
                        </div>
                        <div class="my-4">
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
</template>

<script setup>
import { ref, onMounted ,computed , inject } from 'vue';
import { useStore } from 'vuex';
import { useRouter, useRoute } from 'vue-router';
import profileDom from '/resources/img/profile_dom.png'; 
import useAuth from '@/composables/auth';
import useAuctions from '@/composables/auctions';
import { cmmn } from '@/hooks/cmmn';
import { previousFriday } from 'date-fns';
import useUsers from "@/composables/users";

const { getUser , user } = useUsers();
const router = useRouter();
const photoUrl = ref(profileDom);
const store = useStore();
const swal = inject('$swal')
const profile = ref({
  receive_post : '',
  receive_addr1 : '',
  receive_addr2 : '',
});
const userId = ref(null);
const isDealer = ref(false);
const { openPostcode , closePostcode , wica , wicac } = cmmn();


const triggerFileInput = () => {
  fileInput.value.click();
};

const fileInput = ref(null);

const updateProfile = async () => {

  let payload = {
      dealer: {
          receive_post: profile.value.receive_post,
          receive_addr1: profile.value.receive_addr1,
          receive_addr2: profile.value.receive_addr2,
      }
  };
  console.log(JSON.stringify(payload));
  const formData = new FormData();
  formData.append('user', JSON.stringify(payload.user));

  wica.ntcn(swal)
    .title('수정하시겠습니까?') // 알림 제목
    .icon('Q') //E:error , W:warning , I:info , Q:question
    .callback(async function(result) {
        if (result.isOk) {
            wicac.conn()
            .url(`/api/users/${userId.value}`)
            .param(formData)
            .multipartUpdate()
            .callback(async function(result) {
              console.log(result);
                if(result.isSuccess){
                  swal({
                      icon: "success",
                      showConfirmButton: false,
                      timer: 3000,
                  });
                  //setUserProfileData();
                  location.reload();
                } 

            })
            .post();
        }
    }).confirm();

};

const setUserProfileData = async () => {

  userId.value = store.getters['auth/user'].id;
  
  await getUser(userId.value);
  //console.log(user);

  isDealer.value = user.value?.roles?.includes('dealer') || false;

  if (user) {
    if (isDealer.value) {
      profile.value.receive_post = user.value.dealer.receive_post;
      profile.value.receive_addr1 = user.value.dealer.receive_addr1;
      profile.value.receive_addr2 = user.value.dealer.receive_addr2;
    }
  } 
};

function editPostCode(elementName) {
  openPostcode(elementName)
    .then(({ zonecode, address }) => {
      profile.value.company_post = zonecode;
      profile.value.company_addr1 = address;
    })
}

function editPostCodeReceive(elementName) {
    openPostcode(elementName)
    .then(({ zonecode, address }) => {
      profile.value.receive_post = zonecode;
      profile.value.receive_addr1 = address;
    })
}

onMounted(async () => {
  setUserProfileData();
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
</style>
