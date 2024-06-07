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
          <label for="name">Name:</label>
          <input type="text" v-model="profile.name" id="name" />
        </div>
        <div class="form-group">
          <label for="username">Username:</label>
          <input type="text" v-model="profile.username" id="username" />
        </div>
        <div class="form-group">
          <label for="email">Email:</label>
          <input type="email" v-model="profile.email" id="email" />
        </div>
        <div class="form-group">
          <label for="password">Password:</label>
          <input type="password" v-model="profile.password" id="password" />
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
      </form>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        profile: {
          photoUrl: 'https://via.placeholder.com/100', // 기본 프로필 사진 URL
          name: 'John Doe',
          username: 'john.doe',
          email: 'john.doe@example.com',
          password: '',
        },
      };
    },
    methods: {
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
      updateProfile() {
    
        console.log('Profile updated:', this.profile);
        alert('Profile updated successfully!');
      },
    },
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
  .profile-content{
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
  