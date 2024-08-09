<template>
  <div></div>
  <form @submit.prevent="submitForm">
    <div class="row my-5 mov-wide m-auto">
      <div class="card border-0 shadow-none">
        <div class="d-flex justify-content-start my-3">
          <button type="button" @click="goBackToList" class="back-to-list-button fw-bolder fs-6">
            <span class="icon-arrow-left me-2">←</span>목록으로
          </button>
        </div>
        <h4 class="mt-2">{{ boardText }}</h4>
        <p class="text-secondary opacity-75 fs-6 mb-4">
          {{ boardTextMessage }}
        </p>
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
            <v-select v-model="post.category" :options="categoriesList" :reduce="category => category" label="category" class="form-control" placeholder=""/>
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
      <input type="file" ref="fileInputRef" style="display:none" @change="handleFileUpload">
      <div v-if="boardAttachUrl" class="text-start text-secondary opacity-50">사진 파일: 
        <a :href=boardAttachUrl download>{{ post.board_attach_name }}</a>
        <span class="icon-close-img cursor-pointer" @click="triggerFileDelete()"></span>
      </div>
   <!-- 비밀글 생성 <div class="p-0 my-3 d-flex gap-2">
      <p class="text-secondary opacity-70">비밀글: </p>
      <div class="d-flex align-items-center">
        <p :class="{ 'fw-bolder': isBizChecked }">비밀글을 설정 하시겠습니까?</p>
        <div class="check_box">
          <input type="checkbox" id="ch2" v-model="isBizChecked" class="form-control" />
          <label for="ch2"></label>
        </div>
      </div>
    </div>-->
  </div>
</form>
</template>
<script setup>
import { onMounted, reactive, ref, inject ,computed} from "vue";
import TextEditorComponent from "@/components/TextEditorComponent.vue";
import { useForm, useField, defineRule } from "vee-validate";
import { required, min } from "@/validation/rules";
import { cmmn } from '@/hooks/cmmn';
import axios from 'axios';
import { useRouter, useRoute } from 'vue-router';
import vSelect from 'vue-select';

defineRule("required", required);
defineRule("min", min);
const isBizChecked = ref(false);
const schema = {
  title: "required|min:5",
  content: "required|min:50",
};
const boardText = computed(() => {
  switch(boardId) {
    case 'notice':
      return '공지사항';
    case 'claim':
      return '클레임';
    default:
      return boardId;
  }
});
const boardTextMessage = computed(() => {
  if (boardId === 'notice') {
    return `${boardText.value}을 작성해주세요.`;
  } 
    return '';
  
});
const { validate } = useForm({ validationSchema: schema });
const { value: title } = useField("title", null, { initialValue: "" });
const { value: content } = useField("content", null, { initialValue: "" });
function goBackToList() {
  router.push({ name: 'posts.index', params: { boardId: boardId.value } });
}
const post = reactive({
  title,
  content,
  category: '',
  board_attach : '',
  board_attach_name : '',
  is_secret: 0 
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
const auctionId = route.params.auctionId; 
const boardAttachUrl = ref('');

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
  serializedPost.append('article[is_secret]', isBizChecked.value ? 1 : 0); 
  if (boardId === 'claim') {
    serializedPost.append('article[extra1]', auctionId);
  }
  if(postData.board_attach){
    serializedPost.append('board_attach', postData.board_attach);
  }

  wicac.conn()
  .url(`/api/board/${boardId}/articles`)
  .multipart() 
  .param(serializedPost)
  .callback(function(result) {
      if(result.isSuccess){
        let successMsg = '';
        if(boardId == 'claim'){
          successMsg = '클레임이 성공적으로 저장되었습니다.';
        }else{
          successMsg = '공지사항이 성공적으로 저장되었습니다.';
        }
        router.push({ name: 'posts.index', params: { boardId } });
        wica.ntcn(swal)
        .icon('I')
        .alert(successMsg);
        isLoading.value = false;
      } else {
        wica.ntcn(swal)
        .title('오류가 발생하였습니다.')
        .useHtmlText()
        .icon('E') //E:error , W:warning , I:info , Q:question
        .alert('관리자에게 문의해주세요.');
        //Object.assign(validationErrors, result.msg);
        isLoading.value = false;
      }
  })
  .post();
};

function handleFileUpload(event) {
  const file = event.target.files[0];
  if (file) {
    post.board_attach = file;
    post.board_attach_name = file.name;
    boardAttachUrl.value = URL.createObjectURL(file);
    //console.log("Certification file:", file.name);
  }
}

function triggerFileUpload() {
  if (fileInputRef.value) {
      fileInputRef.value.click();
  } else {
      console.error("파일을 찾을 수 없습니다.");
  }
};

function triggerFileDelete() {
  post.board_attach_name = '';
  post.board_attach='';
  boardAttachUrl.value = '';
}

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
.cursor-pointer{
  cursor: pointer;
}
</style>


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
.cursor-pointer{
  cursor: pointer;
}
</style>
