<template>
    <form @submit.prevent="submitForm">
        <div class="row my-5 mov-wide m-auto">
                <div class="card border-0 shadow-none">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h6 class="mt-3">카테고리</h6>
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
                        <div class="mb-3">
                            <v-select multiple v-model="post.categories" :options="categoryList"
                                      :reduce="category => category.id" label="name" class="form-control" placeholder="Select category"/>
                            <div class="text-danger mt-1">
                                {{ errors.categories }}
                            </div>
                            <div class="text-danger mt-1">
                                <div v-for="message in validationErrors?.categories">
                                    {{ message }}
                                </div>
                            </div>
                        </div>
                        <!-- Title -->
                        <div class="mb-3">
                            <label for="post-title" class="form-label">제목</label>
                            <input v-model="post.title" id="post-title" type="text" class="form-control">
                            <div class="text-danger mt-1">
                                {{ errors.title }}
                            </div>
                            <div class="text-danger mt-1">
                                <div v-for="message in validationErrors?.title">
                                    {{ message }}
                                </div>
                            </div>
                        </div>
                        <!-- Content -->
                        <div class="mb-3">
                            <label for="post-content" class="form-label">컨텐츠 내용</label>
                            <TextEditorComponent v-model="post.content"/>
                            <div class="text-danger mt-1">
                                {{ errors.content }}
                            </div>
                            <div class="text-danger mt-1">
                                <div v-for="message in validationErrors?.content">
                                    {{ message }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-fileupload w-100" @click="triggerFileUpload">
                    파일 첨부
                </button>
                <input type="file" ref="fileInputRef" style="display:none" @change="handleFileUpload" id="file_user_photo">
                <div class="text-start tc-light-gray">사진 파일: {{ fileName }}</div>
            </div>
    </form>
</template>
<script setup>
import { onMounted, reactive, ref } from "vue";
import TextEditorComponent from "@/components/TextEditorComponent.vue";
import useCategories from "@/composables/categories";
import usePosts from "@/composables/posts";
import { useForm, useField, defineRule } from "vee-validate";
import { required, min } from "@/validation/rules";

defineRule("required", required);
defineRule("min", min);

// Define a validation schema
const schema = {
  title: "required|min:5",
  content: "required|min:50",
  categories: "required",
};
// Create a form context with the validation schema
const { validate, errors } = useForm({ validationSchema: schema });
// Define actual fields for validation
const { value: title } = useField("title", null, { initialValue: "" });
const { value: content } = useField("content", null, { initialValue: "" });
const { value: categories } = useField("categories", null, { initialValue: "", label: "category" });
const { categoryList, getCategoryList } = useCategories();
const { storePost, validationErrors, isLoading } = usePosts();
const post = reactive({
  title,
  content,
  categories,
  thumbnail: "",
});

const fileInputRef = ref(null);
const fileName = ref("");

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
    if (form.valid) storePost(post);
  });
}

onMounted(() => {
  getCategoryList();
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
