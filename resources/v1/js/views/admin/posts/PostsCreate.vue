<template>
  <div></div>
  <form @submit.prevent="submitForm" class="pt-2">
    <div class="row my-5 mov-wide m-auto">
      <div class="card border-0 shadow-none">
        <h4 class="mt-2">{{ boardText }}</h4>
        <p class="text-secondary opacity-75 fs-6 mb-4">
          {{ boardTextMessage }}
        </p>
        <div class="card-body">

          <div v-if="boardText === '클레임'">
            <div class="mb-3">
              <!-- <label for="post-title" class="form-label">경매 정보</label> -->
              <div v-if="auctionCarInfo">
                <p class="card-title fw-bolder">
                    <!-- {{ auction.auction_type === '1' ? '공매' : '경매' }} -->
                    {{ auctionCarInfo.car_model ? auctionCarInfo.car_model +' '+ auctionCarInfo.car_model_sub +' '+ auctionCarInfo.car_fuel + ' [ '+ auctionCarInfo.car_no +' ]' : '더 뉴 그랜저 IG 2.5 가솔린 르블랑' }}
                </p>
                <p class="tc-gray mt-0"> {{ auctionCarInfo.car_year ? auctionCarInfo.car_year : '2020' }} 년 |<span class="mx-1">{{ auctionCarInfo.car_km ? auctionCarInfo.car_km : '2.4' }}km</span></p>
                <p class="tc-gray mt-0">{{ auctionCarInfo.car_maker ? auctionCarInfo.car_maker + ' ' + auctionCarInfo.car_model : '현대 소나타' }} ({{ auctionCarInfo.car_grade ? auctionCarInfo.car_grade : 'DN8' }})</p>
              </div>
              <div v-else>
                <p>경매 정보를 불러오는 중입니다...</p>
              </div>
            </div>
          </div>

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
            <label for="post-content" class="form-label">{{ boardText === '클레임' ? '클레임 내용' : '컨텐츠 내용' }}</label>
            <TextEditorComponent v-model="post.content"/>
            <div class="text-danger mt-1">
              <div v-if="validationErrors.content">{{ validationErrors.content }}</div>
            </div>
          </div>
        </div>
        <button type="button" class="btn btn-fileupload w-100 my-3" @click="triggerFileUpload">
          파일 첨부
        </button>
      </div>
      <input type="file" ref="fileInputRef" style="display:none" @change="handleFileUpload" multiple>
      <div v-if="boardAttachUrls.length" class="text-start text-secondary opacity-50">
        <div v-for="(url, index) in boardAttachUrls" :key="index">
          사진 파일: <a :href="url" download>{{ post.board_attach_names[index] }}</a>
          <span class="icon-close-img cursor-pointer" @click="triggerFileDelete(index)"></span>
        </div>
      </div>
      <!-- <div v-if="boardAttachUrls.length" class="text-start text-secondary opacity-50">
        <div v-for="(url, index) in boardAttachUrls" :key="index">
          사진 파일: <a :href="url" download>{{ post.board_attach_names[index] }}</a>
          <span class="icon-close-img cursor-pointer" @click="triggerFileDelete(index)"></span>
        </div>
      </div> -->
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
    <div class="d-flex justify-content-start my-3">
    <button type="button" @click="goBackToList" class="back-to-list-button fw-bolder w-100 fs-6 btn btn-primary">
      <span class="icon-arrow-left me-2">←</span>목록으로
    </button>
  </div>
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
import useAuctions from '@/composables/auctions';
import { initPostSystem } from "@/composables/posts";

defineRule("required", required);
defineRule("min", min);

const { getPost, updatePost, categories, getBoardCategories, addCommentAPI, deleteComment, editComment } = initPostSystem();

const isBizChecked = ref(false);
const schema = {
  title: "required",
  content: "required",
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
  board_attach : [],
  board_attach_names : [],
  is_secret: 0 
});

const fileInputRef = ref(null);
const fileName = ref("");
const isLoading = ref(false);
const categoriesList = ref([]);
const { wicac, wica } = cmmn();
const swal = inject('$swal');
const router = useRouter();
const route = useRoute();
const boardId = route.params.boardId;
const auctionId = route.params.auctionId; 
const boardAttachUrls = ref([]);
const auctionCarInfo = ref('');
const { getAuctionById } = useAuctions();

function stripHtml(html) {
  let tmp = document.createElement("DIV");
  tmp.innerHTML = html;
  return tmp.textContent || tmp.innerText || "";
}

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

const getAuctionCarInfo = async () => {
  try {
    const result = await getAuctionById(auctionId);
    if (result && result.data) {
      auctionCarInfo.value = result.data;
    }
  } catch (error) {
    console.error('Error fetching auction car info:', error);
  }
};

const validationErrors = reactive({
  category: '',
  title: '',
  content: ''
});

function submitForm() {
  // 이전 오류 메시지를 초기화
  validationErrors.category = '';
  validationErrors.title = '';
  validationErrors.content = '';

  validate().then((form) => {
    // 초기화
    let errorMessage = '';

    // 카테고리 선택 여부 확인
    if (boardId === 'notice' && !post.category) {
      validationErrors.category = '카테고리를 선택해주세요.';
      errorMessage += '카테고리\n';
    }

    // 제목과 내용 확인
    if (!post.title) {
      validationErrors.title = '제목을 입력해주세요.';
      errorMessage += '제목\n';
    }
    if (!post.content || stripHtml(post.content).trim() === '') {
      validationErrors.content = '내용을 입력해주세요.';
      errorMessage += '내용\n';
    }

    // 에러 메시지가 있을 경우 경고 표시
    if (errorMessage) {
      swal({
        title: '입력 필요',
        text: `${errorMessage.trim()}이(가) 비어있습니다.`,
        icon: 'warning',
        buttons: {
          confirm: {
            text: '확인',
            className: 'btn btn-primary',
          },
        },
      });
    } else if (form.valid) {
      submitPost(post);
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
  if(postData.board_attach && Array.isArray(postData.board_attach)){
    postData.board_attach.forEach(file => {
      serializedPost.append('board_attach[]', file);
    });
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
        isLoading.value = false;
      }
  })
  .post();
};

function handleFileUpload(event) {
  const files = event.target.files;
  if (files.length) {
    post.board_attach = Array.from(files);
    post.board_attach_names = Array.from(files).map(file => file.name);
    boardAttachUrls.value = Array.from(files).map(file => URL.createObjectURL(file));
  }
}

function triggerFileUpload() {
  if (fileInputRef.value) {
      fileInputRef.value.click();
  } else {
      console.error("파일을 찾을 수 없습니다.");
  }
};

function triggerFileDelete(index) {
  post.board_attach_names.splice(index, 1);
  post.board_attach.splice(index, 1);
  boardAttachUrls.value.splice(index, 1);
}

onMounted(() => {
  getBoardData();
  if (boardText.value === '클레임') {
    getAuctionCarInfo();
  }
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
  width: 100% !important;
}
.cursor-pointer{
  cursor: pointer;
}
</style>
