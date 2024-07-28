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
                  <span class="image-icon-pen"></span>등록
                </p>
              </button>
            </div>
          </div>
          <!-- Category -->
          <div v-if="boardId === 'notice'" class="mb-3">
            <h6 class="mt-3">카테고리</h6>
            <v-select v-model="post.category" :options="categoriesList" :reduce="category => category" label="category" class="form-control" placeholder="Select category"/>
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
      <button type="button" class="btn btn-fileupload w-100" @click="triggerFileUpload">
        파일 첨부
      </button>
      <input type="file" ref="fileInputRef" style="display:none" @change="handleFileUpload" id="file_user_photo">
      <div class="text-start text-secondary opacity-50">사진 파일: {{ fileName }}</div>
    </div>
  </form>
</template>

<script setup>
import { onMounted, reactive, ref, inject } from "vue";
import TextEditorComponent from "@/components/TextEditorComponent.vue";
import { useForm, useField, defineRule } from "vee-validate";
import { required, min } from "@/validation/rules";
import { cmmn } from '@/hooks/cmmn';
import axios from 'axios';
import { useRouter, useRoute } from 'vue-router';
import vSelect from 'vue-select';

defineRule("required", required);
defineRule("min", min);

const schema = {
  title: "required|min:5",
  content: "required|min:50",
};

const { validate } = useForm({ validationSchema: schema });
const { value: title } = useField("title", null, { initialValue: "" });
const { value: content } = useField("content", null, { initialValue: "" });

const post = reactive({
  title,
  content,
  category: '',
  thumbnail: null,
});

const fileInputRef = ref(null);
const fileName = ref("");
const validationErrors = reactive({});
const isLoading = ref(false);
const categoriesList = ref([]);
const { wicac, wica } = cmmn();
const swal = inject('$swal');
const router = useRouter();
const route = useRoute();
const boardId = route.params.boardId;
const auctionId = route.params.auctionId; // Retrieve auctionId from route params

const getBoardData = async () => {
  try {
    const response = await axios.get('/api/board');
    if (Array.isArray(response.data.data)) {
      const board = response.data.data.find(board => board.id === boardId);
      if (board) {
        categoriesList.value = JSON.parse(board.categories);
      }
    }
  } catch (error) {
    console.error('Error fetching board data:', error);
  }
};

function triggerFileUpload() {
  fileInputRef.value.click();
}

function handleFileUpload(event) {
  const file = event.target.files[0];
  if (file) {
    fileName.value = file.name;
    post.thumbnail = file;
  }
}

function submitForm() {
  validate().then((form) => {
    if (form.valid) {
      submitPost(post);
    } else {
      Object.assign(validationErrors, form.errors);
    }
  });
}

const submitPost = async (postData) => {
  if (isLoading.value) return;

  isLoading.value = true;
  validationErrors.value = {};

  const serializedPost = new FormData();
  serializedPost.append('article[title]', postData.title);
  serializedPost.append('article[content]', postData.content);
  serializedPost.append('article[category]', postData.category);
  if (boardId === 'claim') {
    serializedPost.append('article[extra1]', auctionId); // Include auctionId as extra1 when boardId is 'claim'
  }
  if (postData.thumbnail) {
    serializedPost.append('article[thumbnail]', postData.thumbnail);
  }

  try {
    const response = await axios.post(`/api/board/${boardId}/articles`, serializedPost, {
      headers: {
        "content-type": "multipart/form-data"
      }
    });
    router.push({ name: 'posts.index', params: { boardId } });
    wica.ntcn(swal)
      .icon('I')
      .alert('공지사항이 성공적으로 저장되었습니다.');
      console.log("업데이트 정보:",post.category);
  } catch (error) {
    if (error.response?.data) {
      Object.assign(validationErrors, error.response.data.errors);
    }
  } finally {
    isLoading.value = false;
  }
};

onMounted(() => {
  getBoardData();
});
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

.btn-fileupload {
  width: 25% !important;
}
</style>
