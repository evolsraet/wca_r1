<template>
  <div class="container p-5 mov-wide">
    <form @submit.prevent="updateProfile">
      <h4 class="mb-5">내 정보 수정</h4>
      <div class="form-group profile-content">
        <div class="edit-photo-container" @click="triggerFileInput">
          <img :src="photoUrl" alt="Profile Photo" class="profile-photo" />
          <input type="file" ref="fileInput" @change="onFileChange" id="photo" class="d-none" />
        </div>
      </div>
      <!--
      <div class="form-group">
        <label for="name">이름</label>
        <input type="text" v-model="profile.name" id="name" class="form-control" />
      </div>-->
      <div class="form-group">
        <label for="email">이메일</label>
        <input type="text" v-model="profile.email" id="email" class="input-dis form-control" readonly/>
      </div>
      <div v-if="isDealer">
        <div class="form-group">
          <label for="dealerName">딜러 이름</label>
          <input type="text" v-model="profile.name" id="dealerName" class="form-control"/>
        </div>
        <div class="form-group">
          <label for="dealer">소속</label>
          <input type="text" v-model="profile.company" class="form-control" />
        </div>
        <div class="form-group">
          <label for="dealer">소속상사 주소 </label>
          <input type="text" @click="editPostCode('daumPostcodeInput')" v-model="profile.company_post" class="input-dis form-control" readonly/>
          <div>
            <input type="text" v-model="profile.company_addr1" class="input-dis form-control" readonly />
            <button type="button" class="search-btn" @click="editPostCode('daumPostcodeInput')">검색</button>
          </div>
          <input type="text" v-model="profile.company_addr2" class="form-control" />
          <div id="daumPostcodeInput" style="display: none; border: 1px solid; width: 100%; height: 466px; margin: 5px 0px; position: relative">
            <img src="//t1.daumcdn.net/postcode/resource/images/close.png" style="cursor:pointer;position:absolute;right:0px;top:-1px;z-index:1" @click="closePostcode('daumPostcodeInput')">
          </div>
        </div>
        <div class="form-group">
          <label for="dealer">인수차량 도착지 주소</label>
          <input type="text" v-model="profile.receive_post" class="input-dis form-control" readonly />
          <div>
            <input type="text" v-model="profile.receive_addr1" class="input-dis form-control" readonly />
            <button type="button" class="search-btn" @click="editPostCodeReceive('daumPostcodeDealerReceiveInput')">검색</button>
          </div>
          <input type="text" v-model="profile.receive_addr2" class="form-control" />
          <div id="daumPostcodeDealerReceiveInput" style="display: none; border: 1px solid; width: 100%; height: 466px; margin: 5px 0px; position: relative">
            <img src="//t1.daumcdn.net/postcode/resource/images/close.png" style="cursor:pointer;position:absolute;right:0px;top:-1px;z-index:1" @click="closePostcode('daumPostcodeDealerReceiveInput')">
          </div>
        </div>
        <div class="form-group">
          <label for="dealer">소개</label>
          <textarea type="text" v-model="profile.introduce" class="custom-textarea mt-2"></textarea>
        </div>
      </div>
      <button type="submit" class="mt-3 w-100 btn btn-primary">저장</button>
    </form>
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

const router = useRouter();
const photoUrl = ref(profileDom);
const store = useStore();
const swal = inject('$swal')
const profile = ref({
  name: '',
  email: '',
  company: '',
  company_post : '',
  company_addr1 : '',
  company_addr2 : '',
  receive_post : '',
  receive_addr1 : '',
  receive_addr2 : '',
  introduce : '',
  file_user_photo: null
});
const user = computed(() => store.getters['auth/user']);
const { openPostcode , closePostcode , wica , wicac } = cmmn();
const isDealer = computed(() => user.value?.roles?.includes('dealer'));

const triggerFileInput = () => {
  fileInput.value.click();
};

const fileInput = ref(null);
let userId ;

const onFileChange = (e) => {
  const file = e.target.files[0];
  if (file) {
    profile.value.file_user_photo = file;
    //console.log(profile.value.file_user_photo);
    const reader = new FileReader();
    reader.onload = (e) => {
      photoUrl.value = e.target.result;
    };
    reader.readAsDataURL(file);
  }
};

const updateProfile = async () => {
  let payload = {
      user : {

      },
      dealer: {
          name: profile.value.name,
          company: profile.value.company,
          company_post: profile.value.company_post,
          company_addr1:profile.value.company_addr1,
          company_addr2: profile.value.company_addr2,
          introduce: profile.value.introduce,
          receive_post: profile.value.receive_post,
          receive_addr1: profile.value.receive_addr1,
          receive_addr2: profile.value.receive_addr2,
      }
  };
  console.log(JSON.stringify(payload));
  const formData = new FormData();
  formData.append('user', JSON.stringify(payload.user));
  formData.append('dealer', JSON.stringify(payload.dealer));

  if (profile.value.file_user_photo) {
    formData.append('file_user_photo', profile.value.file_user_photo);
  }
  wica.ntcn(swal)
    .title('수정하시겠습니까?') // 알림 제목
    .icon('Q') //E:error , W:warning , I:info , Q:question
    .callback(async function(result) {
        if (result.isOk) {
            wicac.conn()
            .url(`/api/users/${userId}`)
            .param(formData)
            .multipart()
            .callback(async function(result) {
                if(result.isSuccess){
                  swal({
                      icon: "success",
                      showConfirmButton: false,
                      timer: 3000,
                  });
                  setUserProfileData();
                  location.reload();
                }
            })
            .post();
        }
    }).confirm();

};

const setUserProfileData = async () => {
  const user = store.getters['auth/user'];
  console.log(user);
  userId = user.id;

  if (user) {
    //profile.value.name = user.name;
    profile.value.email = user.email;
    if (isDealer) {
      profile.value.name = user.dealer.name;
      profile.value.company = user.dealer.company;
      profile.value.company_post = user.dealer.company_post;
      profile.value.company_addr1 = user.dealer.company_addr1;
      profile.value.company_addr2 = user.dealer.company_addr2;
      profile.value.receive_post = user.dealer.receive_post;
      profile.value.receive_addr1 = user.dealer.receive_addr1;
      profile.value.receive_addr2 = user.dealer.receive_addr2;
      profile.value.introduce = user.dealer.introduce;
    }
    if (user.files) {
      photoUrl.value = user.files.file_user_photo[0].original_url;
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
  await store.dispatch("auth/getUser");
  setUserProfileData();
});
</script>

<style scoped>
.profile {
  max-width: 400px;
  margin: 0 auto;
  font-family: Arial, sans-serif;
}

.form-group {
  margin-bottom: 1rem;
}

.profile-content {
  display: flex;
  flex-direction: column;
  align-content: center;
  align-items: center;
}

label {
  display: block;
  margin-bottom: 0.5rem;
}

input[type="text"],
input[type="email"],
input[type="password"] {
  width: 100%;
  padding: 0.5rem;
  box-sizing: border-box;
  border: 1px solid #ccc;
}
.profile-photo {
  width: 100%;
  height: 100%;
  object-fit: cover;
  position: absolute;
  top: 0;
  left: 0;
}

.d-none {
  display: none;
}
</style>
