<template>
  <form @submit.prevent="submitForm">
    <div class="row my-2 mov-wide m-auto container">
      <div class="card border-0 shadow-none">
        <h4 class="mt-4">{{ boardText }}</h4>
        <p class="text-secondary opacity-75 fs-6 mb-4">
          {{ boardTextMessage }}
        </p>
        <div class="card-body">
          <div class="d-flex justify-content-end">
            <div>
              <button v-if="!navigatedThroughHandleRowClick" :disabled="isLoading" class="primary-btn">
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
            <label for="post-category" class="form-label">카테고리</label>
            <select v-model="post.category" id="post-category" class="form-control" :disabled="navigatedThroughHandleRowClick">
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
            <input v-model="post.title" id="post-title" type="text" class="form-control" :disabled="navigatedThroughHandleRowClick">
            <div class="text-danger mt-1">
              <div v-if="validationErrors.title">{{ validationErrors.title }}</div>
            </div>
          </div>
          <!-- Content -->
          <div class="mb-3">
            <label for="post-content" class="form-label">컨텐츠 내용</label>
            <textarea v-if="navigatedThroughHandleRowClick" v-model="plainTextContent" id="post-content" class="form-control" rows="10" disabled style="resize: none;"></textarea>
            <TextEditorComponent v-else v-model="post.content"/>
            <div class="text-danger mt-1">
              <div v-if="validationErrors.content">{{ validationErrors.content }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
  <!-- Comments Section -->
  <div v-if="!isDealer && boardId === 'claim' && navigatedThroughHandleRowClick" class="row my-5 mov-wide m-auto container">
      <!-- Comments List -->
      <label for="comments" class="form-label">코멘트</label>
      <div class="comment-list">
        <div v-if="post.comments && post.comments.length === 0" class="no-comments text-center tc-primary">
          코멘트가 없습니다.
        </div>
        <div v-else>
          <div v-for="(comment, index) in post.comments" :key="comment.id" class="comment-item" :class="{ 'new-comment-highlight': comment.isNew }">
            <div class="d-flex align-items-start">
              <div class="comment-body">
                <div v-if="!editCommentIndex.includes(index)">
                  <div class="d-flex justify-content-between">
                    <p class="comment-author">{{ comment.user.name }}<span class="">({{ comment.user.email }})</span></p>
                    <p class="comment-date">{{ comment.created_at }}</p>
                  </div>
                  <p class="comment-content">{{ comment.content }}</p>
                </div>
                <div v-else>
                  <textarea v-model="comment.content" class="form-control" rows="6"></textarea>
                  <button @click="saveComment(index, comment.id)" class="btn btn-primary mt-2">수정</button>
                </div>
                <div v-if="isCommentByCurrentUser(comment.user_id)" class="d-flex justify-content-end align-items-center">
                  <div class="badge" @click="toggleEditComment(index)">
                    <div class="pointer icon-edit-img"></div>
                  </div>
                  <div @click.stop class="ms-2 badge web_style">
                    <div @click.prevent="handleDeleteComment(comment.id)" class="pointer icon-trash-img"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>


    <!-- New Comment Form -->
    <div class="new-comment">
      <label for="new-comment-content" class="form-label">새 코멘트</label>
      <textarea v-model="newComment.content"class="form-contro l" rows="3" placeholder="댓글을 입력하세요..."></textarea>
      <div class="d-flex justify-content-end my-2">
        <button @click="addComment" class="btn btn-primary mt-2">댓글 추가</button>
      </div>
    </div>
  </div>
  <div v-if="isDealer">
  <Footer />
</div>
</template>

<script setup>
import Footer from "@/views/layout/footer.vue";
import { onMounted, reactive, ref, inject, watchEffect, computed } from "vue";
import TextEditorComponent from "@/components/TextEditorComponent.vue";
import { useForm, defineRule } from "vee-validate";
import { required, min } from "@/validation/rules";
import { initPostSystem } from "@/composables/posts";
import { useRouter, useRoute } from 'vue-router';
import { useStore } from 'vuex';
import { cmmn } from '@/hooks/cmmn';
defineRule("required", required);
defineRule("min", min);
const { wica } = cmmn();
const { validate } = useForm();
const store = useStore();

const { post: postData, getPost, updatePost, validationErrors, isLoading, categories, getBoardCategories,addCommentAPI,deleteComment,editComment  } = initPostSystem();
const isCommentByCurrentUser = (commentUserId) => {
  return user.value.id === commentUserId;
};
const post = reactive({
  title: '',
  content: '',
  category: '',
  comments: []
});

const newComment = reactive({
  content: ''
});

const plainTextContent = ref('');
const swal = inject('$swal');
const router = useRouter();
const route = useRoute();
const postId = route.params.id;
const boardId = ref(route.params.boardId);
const navigatedThroughHandleRowClick = ref('false');
const user = computed(() => store.getters['auth/user']);

// 댓글 수정 상태를 관리
const editCommentIndex = ref([]);

// 수정 아이콘 클릭 시 호출
function toggleEditComment(index) {
  if (editCommentIndex.value.includes(index)) {
    // 이미 수정 중인 경우 편집 모드 해제
    editCommentIndex.value = editCommentIndex.value.filter(i => i !== index);
  } else {
    // 수정 모드로 전환
    editCommentIndex.value.push(index);
  }
}

// 댓글 저장 시 호출되는 함수
async function saveComment(index, commentId) {
  try {
    const comment = post.comments[index];
    await editComment(commentId, comment.content);
    editCommentIndex.value = editCommentIndex.value.filter(i => i !== index);
  } catch (error) {
    console.error('Error saving comment:', error);
    swal({
      icon: 'error',
      title: '댓글 수정 중 오류가 발생했습니다.',
    });
  }
}

const isAdmin = ref(false); 
const isDealer = ref(false);

const boardText = computed(() => {
  switch(boardId.value) {
    case 'notice':
      return '공지사항';
    case 'claim':
      return '클레임';
    default:
      return boardId.value;
  }
});

const boardTextMessage = computed(() => {
  if (boardId.value === 'notice') {
    return `빠르고 신속하게 ${boardText.value} 전해드립니다.`;
  } else if (boardId.value === 'claim') {
    return `빠르고 신속하게 처리 해드립니다`;
  } else {
    return '';
  }
});

function stripHtml(html) {
  const tempDiv = document.createElement('div');
  tempDiv.innerHTML = html;
  return tempDiv.textContent || tempDiv.innerText || '';
}

onMounted(async () => {
  navigatedThroughHandleRowClick.value = route.query.navigatedThroughHandleRowClick == 'true';

  await getBoardCategories();
  await getPost(boardId.value, postId);
  if (postData.value) {
    post.title = postData.value.title;
    post.content = postData.value.content;
    post.category = postData.value.category || '';
    post.comments = postData.value.comments || [];
    plainTextContent.value = stripHtml(postData.value.content);
  }
});

watchEffect(() => {
  if (postData.value) {
    post.title = postData.value.title;
    post.content = postData.value.content;
    post.category = postData.value.category || '';
    post.comments = postData.value.comments || [];
    plainTextContent.value = stripHtml(postData.value.content);
  }
});

/* 글 수정시 */ 
async function submitForm() {
  const form = await validate();
  if (form.valid) {
    try {
      const updateData = {
        title: post.title,
        content: post.content,
        comments: post.comments,
      };

      if (boardId.value === 'notice') {
        updateData.category = post.category;
      }

      const response = await updatePost(boardId.value, postId, updateData);
      post.title = response.data.title;
      post.content = response.data.content;
      post.category = response.data.category;
      post.comments = response.data.comments;
      
      swal({
        icon: 'success',
        title: 'Post updated successfully'
      });
      router.push({ name: 'posts.index', params: { boardId: boardId.value } }); 
    } catch (error) {
      if (error.response?.data) {
        Object.assign(validationErrors, error.response.data.errors);
      }
    }
  } else {
    Object.assign(validationErrors, form.errors);
  }
}

async function handleDeleteComment(commentId) {
  await deleteComment(commentId);
}

/* 코멘트 추가 (댓글작성시) */ 
async function addComment() {
  if (newComment.content.trim()) {
    try {
      const commentData = {
        commentable_id: postId,
        content: newComment.content
      };
      const response = await addCommentAPI(commentData);
      const newCommentData = response.data;

      newCommentData.isNew = true;
      post.comments = [newCommentData, ...post.comments];

      setTimeout(() => {
        newCommentData.isNew = false;
      }, 3000);

      newComment.content = '';
    } catch (error) {
      swal({
        icon: 'error',
        title: '댓글 추가 중 오류가 발생했습니다.',
      });
      console.error('Error adding comment:', error);
    }
  } else {
    swal({
      icon: 'warning',
      title: '댓글 내용을 입력해주세요.',
    });
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

.comment-list {
  margin-top: 20px;
  padding-top: 10px;
  max-height: 500px; 
  overflow-y: auto; 
  overflow-x: hidden;
  border-top: 1px solid #ddd;
}

.comment-item {
  background-color: #fff;
  border-radius: 8px;
  border: 1px solid #e1e1e1;
  padding: 15px;
  margin-bottom: 15px;
  box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.05);
}

.comment-avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  margin-right: 15px;
}

.comment-body {
  flex: 1;
  white-space: pre-wrap; 
  width: -webkit-fill-available;
}

.comment-author {
  font-weight: 600;
  margin-bottom: 5px;
  color: #333;
}

.comment-date {
  font-size: 0.85em;
  color: #999;
}

.comment-content {
  white-space: normal; 
  overflow: visible; 
  text-overflow: clip; 
  max-width: none; 
  word-wrap: break-word; 
}

</style>
