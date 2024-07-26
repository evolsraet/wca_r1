<template>
  <form @submit.prevent="submitForm">
    <div class="row my-5 mov-wide m-auto">
      <div class="card border-0 shadow-none">
        <div class="card-body">
          <div class="d-flex justify-content-end">
            <div>
              <button :disabled="isLoading" class="primary-btn">
                <div v-show="isLoading" class=""></div>
                <span v-if="isLoading">Processing...</span>
                <p class="d-flex lh-base justify-content-center gap-2" v-else>
                  <span class="image-icon-pen"></span>수정
                </p>
              </button>
            </div>
          </div>
          <!-- Category -->
          <div class="mb-3" v-if="boardId === 'notice'">
            <label for="post-category" label="category" class="form-label">카테고리</label>
            <select v-model="post.category" id="post-category" class="form-control">
              <option v-for="category in categories" :key="category" :value="category">
                {{ category }}
              </option>
            </select>
            <div class="text-danger mt-1">
              <div v-if="validationErrors.category">{{ validationErrors.category }}</div>
            </div>
          </div>
          <!-- Title -->
          <div class="mb-3">
            <label for="post-title" class="form-label">제목</label>
            <input v-model="post.title" id="post-title" type="text" class="form-control">
            <div class="text-danger mt-1">
              <div v-if="validationErrors.title">{{ validationErrors.title }}</div>
            </div>
          </div>
          <!-- Content -->
          <div class="mb-3">
            <label for="post-content" class="form-label">컨텐츠 내용</label>
            <TextEditorComponent v-model="post.content"/>
            <div class="text-danger mt-1">
              <div v-if="validationErrors.content">{{ validationErrors.content }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
</template>

<script setup>
import { onMounted, reactive, ref, inject, watchEffect } from "vue";
import TextEditorComponent from "@/components/TextEditorComponent.vue";
import { useForm, defineRule } from "vee-validate";
import { required, min } from "@/validation/rules";
import { initPostSystem } from "@/composables/posts";
import { useRouter, useRoute } from 'vue-router';

defineRule("required", required);
defineRule("min", min);

const { validate } = useForm();
const { post: postData, getPost, updatePost, validationErrors, isLoading, categories, getBoardCategories } = initPostSystem();

const post = reactive({
  title: '',
  content: '',
  category: ''
});

const swal = inject('$swal');
const router = useRouter();
const route = useRoute();
const postId = route.params.id;
const boardId = route.params.boardId;

onMounted(async () => {
  await getBoardCategories();
  getPost(boardId, postId);
});

watchEffect(() => {
  if (postData.value) {
    post.title = postData.value.title;
    post.content = postData.value.content;
    post.category = postData.value.category || '';
  }
});

async function submitForm() {
  const form = await validate();
  if (form.valid) {
    try {
      const updateData = {
        title: post.title,
        content: post.content,
      };

      if (boardId === 'notice') {
        updateData.category = post.category;
      }

      const response = await updatePost(boardId, postId, updateData);
      post.title = response.data.title;
      post.content = response.data.content;
      post.category = response.data.category;
      
      swal({
        icon: 'success',
        title: 'Post updated successfully'
      });
      router.push({ name: 'posts.index', params: { boardId } }); 
    } catch (error) {
      if (error.response?.data) {
        Object.assign(validationErrors, error.response.data.errors);
      }
    }
  } else {
    Object.assign(validationErrors, form.errors);
  }
}
</script>

<style scoped>
.primary-btn {
  width: 90px;
  border-radius: 30px;
  height: 38px !important;
}

.image-icon-pen {
  width: 18px;
  height: 18px;
  line-height: unset;
}
</style>
