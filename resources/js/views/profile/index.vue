<template>
  <div class="container p-5 mov-wide">
    <form @submit.prevent="updateProfile">
      <h4 class="mb-5">내 정보 수정</h4>
      <div class="form-group profile-content">
        <div class="edit-photo-container" @click="triggerFileInput">
          <img :src="profile.photoUrl" alt="Profile Photo" class="profile-photo" />
          <input type="file" ref="fileInput" @change="onFileChange" id="photo" class="d-none" />
        </div>
      </div>
      <div class="form-group">
        <label for="name">이름</label>
        <input type="text" v-model="profile.name" id="name" class="form-control" />
      </div>
      <div class="form-group">
        <label for="email">이메일</label>
        <input type="text" v-model="profile.email" id="email" class="form-control" />
      </div>
      <div v-if="isDealer">
        <div class="form-group">
          <label for="dealerName">딜러 이름</label>
          <input type="text" v-model="profile.dealer.name" id="dealerName" class="form-control" />
        </div>
        <div class="form-group">
          <label for="dealer">소속</label>
          <input type="text" v-model="profile.dealer.company" class="form-control" />
        </div>
        <div class="form-group">
          <label for="dealer">회사 주소 </label>
          <input type="text" v-model="profile.dealer.company_post" class="form-control" />
          <input type="text" v-model="profile.dealer.company_addr1" class="form-control" />
          <input type="text" v-model="profile.dealer.company_addr2" class="form-control" />
        </div>
        <div class="form-group">
          <label for="dealer">받는 주소</label>
          <input type="text" v-model="profile.dealer.receive_post" class="form-control" />
          <input type="text" v-model="profile.dealer.receive_addr1" class="form-control" />
          <input type="text" v-model="profile.dealer.receive_addr2" class="form-control" />
        </div>
        <div class="form-group">
          <label for="dealer">소개</label>
          <textarea type="text" v-model="profile.dealer.introduce" class="form-control"></textarea>
        </div>
      </div>
      <button type="submit" class="mt-3 w-100 btn btn-primary">저장</button>
    </form>
  </div>
</template>

<script setup>
import { ref, onMounted ,computed } from 'vue';
import { useStore } from 'vuex';
import profileDom from '/resources/img/profile_dom.png'; 
import useAuth from '@/composables/auth';
import useAuctions from '@/composables/auctions';

const store = useStore();

const profile = ref({
  photoUrl: profileDom,
  name: '',
  email: '',
  dealer: {
    name: '',
    company: ''
  },
  file_user_photo: null
});
const user = computed(() => store.getters['auth/user']);

const isDealer = computed(() => user.value?.roles?.includes('dealer'));

const triggerFileInput = () => {
  fileInput.value.click();
};

const fileInput = ref(null);

const onFileChange = (e) => {
  const file = e.target.files[0];
  if (file) {
    profile.value.file_user_photo = file;
    const reader = new FileReader();
    reader.onload = (e) => {
      profile.value.photoUrl = e.target.result;
    };
    reader.readAsDataURL(file);
  }
};

const updateProfile = async () => {
  const formData = new FormData();
  formData.append('user', JSON.stringify(profile.value));
  if (profile.value.file_user_photo) {
    formData.append('file_user_photo', profile.value.file_user_photo);
  }

  try {
    const response = await axios.post('/api/users', formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    });
    console.log('Profile updated:', response.data);
    alert('Profile updated successfully!');
  } catch (error) {
    console.error('Error updating profile:', error);
  }
};

const setUserProfileData = () => {
  const user = store.getters['auth/user'];
  if (user) {
    profile.value.name = user.name;
    profile.value.email = user.email;
    if (isDealer) {
      profile.value.dealer.name = user.dealer.name;
      profile.value.dealer.company = user.dealer.company;
    }
    if (user.file_user_photo) {
      profile.value.photoUrl = user.file_user_photo;
    }
  }
};

onMounted(async () => {
  await store.dispatch('auth/fetchUser');
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
