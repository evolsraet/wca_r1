<template>
    <div class="row my-5">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <!-- Title -->
                    <div class="mb-3">
                        <label for="post-title" class="form-label">
                            Title
                        </label>
                        <input v-model="post.title" id="post-title" type="text" class="form-control" readonly>
                    </div>
                    <!-- Content -->
                    <div class="mb-3">
                        <label for="post-content" class="form-label">
                            Content
                        </label>
                        <TextEditorComponent v-model="post.content" readonly />
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6>Category</h6>
                    <!-- Category -->
                    <div class="mb-3">
                        <v-select multiple v-model="post.categories" :options="categoryList"
                                  :reduce="category => category" label="category" class="form-control" placeholder="Select category" disabled />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { onMounted, reactive, watchEffect } from "vue";
import { useRoute } from "vue-router";
import { initPostSystem } from "@/composables/posts";
import TextEditorComponent from "@/components/TextEditorComponent.vue";
import vSelect from "vue-select";

const { post: postData, getPost, categories: categoryList, getBoardCategories } = initPostSystem();

const post = reactive({
    title: '',
    content: '',
    categories: [],
    thumbnail: ''
});

const route = useRoute();

onMounted(() => {
    getPost(route.params.id);
    getBoardCategories();
});

watchEffect(() => {
    if (postData.value) {
        post.title = postData.value.title;
        post.content = postData.value.content;
        post.categories = postData.value.categories;
    }
});
</script>

<style scoped>
.img-thumbnail {
    max-width: 100%;
    height: auto;
}
</style>
