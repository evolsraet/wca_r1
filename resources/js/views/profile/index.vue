<template>
  <div class="container p-5">
    <form @submit.prevent="updateProfile">
      <div class="form-group profile-content">
        <div class="photo-container" @click="triggerFileInput">
          <img :src="profile.photoUrl" alt="Profile Photo" class="profile-photo" />
          <input type="file" ref="fileInput" @change="onFileChange" id="photo" class="d-none" />
        </div>
      </div>
      <div class="form-group">
        <label for="name">이름</label>
        <input type="text" v-model="profile.name" id="name" />
      </div>
      <div class="form-group">
        <label for="email">이메일</label>
        <input type="text" v-model="profile.email" id="email" />
      </div>
      <div v-if="isDealer">
        <div class="form-group">
          <label for="dealerName">딜러 이름</label>
          <input type="text" v-model="profile.dealer.name" id="dealerName" />
        </div>
        <div class="form-group">
          <label for="dealer">소속</label>
          <input type="text" v-model="profile.dealer.company" />
        </div>
      </div>
      <button type="submit" class="mt-3 w-100 btn btn-primary">저장</button>
    </form>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';

export default {
  data() {
    return {
      profile: {
        photoUrl: 'https://via.placeholder.com/100', // 기본 프로필 사진 URL
        name: '',
        email: '',
        dealer: {
          name: '',
          company: ''
        },
        file_user_photo: null // Added to hold the photo file
      }
    };
  },
  computed: {
    ...mapGetters('auth', ['user', 'isDealer', 'isUser'])
  },
  methods: {
    ...mapActions('auth', ['fetchUser']),
    triggerFileInput() {
      this.$refs.fileInput.click();
    },
    onFileChange(e) {
      const file = e.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
          this.profile.photoUrl = e.target.result;
        };
        reader.readAsDataURL(file);
      }
    },
    async updateProfile() {
      const formData = new FormData();
      formData.append('user', JSON.stringify(this.profile));
      if (this.profile.file_user_photo) {
        formData.append('file_user_photo', this.profile.file_user_photo);
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
    },
    setUserProfileData() {
      if (this.user) {
        this.profile.name = this.user.name;
        this.profile.email = this.user.email;
        if (this.isDealer) {
          this.profile.dealer.name = this.user.dealer.name;
          this.profile.dealer.company = this.user.dealer.company;
        }
        if (this.user.file_user_photo) {
          this.profile.photoUrl = this.user.file_user_photo;
        }
      }
    }
  },
  async mounted() {
    await this.fetchUser();
    this.setUserProfileData();
  }
};
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

button:hover {
  background-color: #0056b3;
}

.photo-container {
  display: flex;
  align-items: center;
  margin-bottom: 1rem;
  cursor: pointer;
}

.profile-photo {
  width: 100px;
  height: 100px;
  border-radius: 50%;
  object-fit: cover;
  margin-right: 1rem;
}

.d-none {
  display: none;
}
</style>
