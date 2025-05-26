
<template>
  <div class="container p-5">
    <h4 class="mb-5">내 정보 수정</h4>
    <div>
          <div class="card border-0 shadow-none">
              <div class="card-body">
    <!--
    <form @submit.prevent="updateProfile">
      <div class="form-group profile-content">
        <div class="img-form">
        <div class="edit-photo-container" @click="triggerFileInput">
          <img :src="photoUrl" alt="Profile Photo" class="profile-photo" />
          <input type="file" ref="fileInput" @change="onFileChange" id="photo" class="d-none" />
        </div>
        <div v-if="photoUrl !== profileDom" class="icon-trash-img"></div>
        </div>
      </div>
      <h5 class="text-secondary opacity-50 fs-5 mb-4">&#8251<span class="mx-2">정보 수정 시 심사를 받으실 수 있습니다.</span></h5>
      <div class="form-group">
        <label for="name">가입일자</label>
        <input type="text" v-model="user.created_at" id="createdAt" class="input-dis form-control" readonly/>
      </div>
      <div class="form-group">
        <label for="name">이름</label>
        <input type="text" v-model="profile.name" id="name" class="form-control" />
      </div>
      <div class="form-group">
        <label for="name">전화번호</label>
        <input type="text" id="phone" v-model="profile.phone" class="form-control" />
      </div>
      <div class="form-group">
        <label for="email">이메일</label>
        <input type="text" v-model="profile.email" id="email" class="input-dis form-control" readonly/>
      </div>
      <div class="form-group">
        <label for="email">변경 비밀번호</label>
        <input autocomplete="one-time-code" type="password" v-model="profile.password" id="password" class="form-control" placeholder="6~8자리 숫자,영어,특수문자 혼합"/>
      </div>
      <div class="form-group">
        <label for="email">비밀번호 확인</label>
        <input autocomplete="one-time-code" type="password" v-model="profile.password_confirmation" id="password_confirmation" class="form-control"/>
      </div>
      <div v-if="isDealer">
        <div class="form-group">
          <label for="dealerName">딜러 이름</label>
          <input type="text" v-model="profile.dealer_name" id="dealerName" class="form-control"/>
        </div>
        <div class="form-group">
          <label for="dealer">소속</label>
          <input type="text" v-model="profile.company" class="form-control" />
    </div>
        <div class="form-group">
          <label for="dealer">소속상사 주소 </label>
          <input type="text" @click="editPostCode('daumPostcodeInput')" v-model="profile.company_post" class="input-dis form-control" readonly/>
          <div class="input-with-button">
        <input type="text" @click="editPostCode('daumPostcodeInput')" v-model="profile.company_post" class="input-dis form-control" readonly/>
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
          <div class="input-with-button">
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
    -->

    <div class="d-flex align-items-center">
      <div class="mov-wide">
        <UserRegisterForm/>
      </div>
    </div>

    </div>
    </div>
  </div>
</div>
</template>

<script setup>
  import UserRegisterForm from "@/views/import/UserRegister.vue";
</script>

<script >
/*
import { ref, onMounted ,computed , inject } from 'vue';
import { useStore } from 'vuex';
import { useRouter, useRoute } from 'vue-router';
import profileDom from '/../../../../../resources/v1/img/profile_dom.png'; 
import useAuth from '@/composables/auth';
import useAuctions from '@/composables/auctions';
import { cmmn } from '@/hooks/cmmn';
import { previousFriday } from 'date-fns';
import useUsers from "@/composables/users";
import UserRegisterForm from "@/views/import/UserRegister.vue";
*/

/*
const { getUser , user } = useUsers();
const router = useRouter();
const photoUrl = ref(profileDom);
const store = useStore();
const swal = inject('$swal')
*/
/*
const profile = ref({
  name: '',
  dealer_name: '',
  password: '',
  password_confirmation : '',
  email: '',
  phone: '',
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
*/

/*
const userId = ref(null);
const isDealer = ref(false);
const { openPostcode , closePostcode , wica , wicac } = cmmn();


const triggerFileInput = () => {
  fileInput.value.click();
};

const fileInput = ref(null);

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
*/
/*
const updateProfile = async () => {

  let payload = {
      user : {
        name : profile.value.name,       
      },
      dealer: {
          name: profile.value.dealer_name,
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
  if (profile.value.password && profile.value.password !== '') {
    payload.user.password = profile.value.password;
  }

  if (profile.value.password_confirmation && profile.value.password_confirmation !== '') {
    payload.user.password_confirmation = profile.value.password_confirmation;
  }
  console.log(JSON.stringify(payload));
  const formData = new FormData();
  formData.append('user', JSON.stringify(payload.user));
  if(isDealer){
    formData.append('dealer', JSON.stringify(payload.dealer));
  }

  if (profile.value.file_user_photo) {
    formData.append('file_user_photo', profile.value.file_user_photo);
  }
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
*/

/*
function fileExstCheck(info){
    if(info.hasOwnProperty('files')){
        if(info.files.hasOwnProperty('file_user_photo')){
            if(info.files.file_user_photo[0].hasOwnProperty('original_url')){
                photoUrl.value = info.files.file_user_photo[0].original_url;
            }
        }
    }
}
*/

/*
const setUserProfileData = async () => {

  userId.value = store.getters['auth/user'].id;
  
  await getUser(userId.value);
  //console.log(user);

  isDealer.value = user.value?.roles?.includes('dealer') || false;
  
  if (user) {
    profile.value.name = user.value.name;
    profile.value.phone = user.value.phone;
    profile.value.email = user.value.email;
    if (isDealer.value) {
      profile.value.dealer_name = user.value.dealer.name;
      profile.value.company = user.value.dealer.company;
      profile.value.company_post = user.value.dealer.company_post;
      profile.value.company_addr1 = user.value.dealer.company_addr1;
      profile.value.company_addr2 = user.value.dealer.company_addr2;
      profile.value.receive_post = user.value.dealer.receive_post;
      profile.value.receive_addr1 = user.value.dealer.receive_addr1;
      profile.value.receive_addr2 = user.value.dealer.receive_addr2;
      profile.value.introduce = user.value.dealer.introduce;
    }
    if (user.value.files) {
      fileExstCheck(user.value);
    }
  }
    
};
*/
/*
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
*/
/*
onMounted(async () => {
  //await store.dispatch("auth/getUser");
  setUserProfileData();
});
*/
</script>
<!--
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

.img-form {
    position: relative;
    display: inline-block;
  }
  .icon-trash-img {
    position: absolute;
    bottom: 18px;
    right: -23px;
    z-index: 10;
    cursor: pointer;
  }
</style>
-->
<style scoped lang="scss">
@media (max-width: 991px){
.p-5{
  padding: 2rem 0.5rem 1rem 0.5rem !important;
}
}

@media (min-width: 992px) {
  .mov-wide {
      margin: 0 auto;
      width: 35rem !important;
      padding-top: 20px !important;
      position: relative;
  }
}

@media (max-width: 992px) {
  .mov-wide {
      width: 100% !important;
  }
}


</style>