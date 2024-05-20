<template>
    <div class="p-3 row justify-content-center my-2">
        <div class="col-md-12">
        </div>
                 <router-link
                        :to="{ name: 'posts.create' }"
                        class="border-red-write"
                    >
                    <div class="image-icon-pen">

                        </div>
                    </router-link>
                    <div class="search-type2 mb-5">
                    <div class="border-xsl">
                        <div class="image-icon-excel">

                        </div>
                    </div>
                    <input type="text" placeholder="검색어"  v-model="search_title" style="width: auto !important;">
                            <button type="button" class="search-btn">검색</button>
                        </div>
                    <div class="container mb-3">
                    <div class="d-flex justify-content-end">
                        <div class="text-start status-selector">
                        <input type="radio" name="status" value="all" id="all" hidden checked @change="setFilter('all')">
                        <label for="all" class="mx-2">전체</label>

                        <input type="radio" name="status" value="ing" id="ongoing" hidden @change="setFilter('ing')">
                        <label for="ongoing">일반</label>

                        <input type="radio" name="status" value="done" id="completed" hidden @change="setFilter('done')">
                        <label for="completed" class="mx-2">딜러</label>
                    </div>
                    </div>
                    </div>
                    <div class="o_table_mobile my-5">
                    <div class="tbl_basic tbl_dealer">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 justify-content-center">
                                        <div
                                            class="flex flex-row items-center justify-content-center justify-between cursor-pointer"
                                            @click="
                                                updateOrdering('created_at')
                                            "
                                        >
                                            <div
                                                class="leading-4 font-medium text-gray-500 uppercase tracking-wider"
                                                :class="{
                                                    'font-bold text-blue-600':
                                                        orderColumn ===
                                                        'created_at',
                                                }"
                                            >
                                                등록일
                                            </div>
                                            <div class="select-none">
                                                <span
                                                    :class="{
                                                        'text-blue-600':
                                                            orderDirection ===
                                                                'asc' &&
                                                            orderColumn ===
                                                                'created_at',
                                                        hidden:
                                                            orderDirection !==
                                                                '' &&
                                                            orderDirection !==
                                                                'asc' &&
                                                            orderColumn ===
                                                                'created_at',
                                                    }"
                                                    >&uarr;</span
                                                >
                                                <span
                                                    :class="{
                                                        'text-blue-600':
                                                            orderDirection ===
                                                                'desc' &&
                                                            orderColumn ===
                                                                'created_at',
                                                        hidden:
                                                            orderDirection !==
                                                                '' &&
                                                            orderDirection !==
                                                                'desc' &&
                                                            orderColumn ===
                                                                'created_at',
                                                    }"
                                                    >&darr;</span
                                                >
                                            </div>
                                        </div>
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 text-left">
                                        <span
                                            class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider"
                                            >카테고리</span
                                        >
                                    </th>
                                    <!--<th class="px-6 py-3 text-left">
                                        <div class="flex flex-row justify-content-center">
                                            <div
                                                class="font-medium text-uppercase"
                                            >
                                                Thumbnail
                                            </div>
                                        </div>
                                    </th> -->
                                    <th class="px-6 py-3 text-left">
                                        <div
                                            class="flex flex-row justify-content-center"
                                            @click="updateOrdering('title')"
                                        >
                                            <div
                                                class="font-medium text-uppercase"
                                                :class="{
                                                    'font-bold text-blue-600':
                                                        orderColumn === 'title',
                                                }"
                                            >
                                              제목
                                            </div>
                                            <div class="select-none">
                                                <span
                                                    :class="{
                                                        'text-blue-600':
                                                            orderDirection ===
                                                                'asc' &&
                                                            orderColumn ===
                                                                'title',
                                                        hidden:
                                                            orderDirection !==
                                                                '' &&
                                                            orderDirection !==
                                                                'asc' &&
                                                            orderColumn ===
                                                                'title',
                                                    }"
                                                    >&uarr;</span
                                                >
                                                <span
                                                    :class="{
                                                        'text-blue-600':
                                                            orderDirection ===
                                                                'desc' &&
                                                            orderColumn ===
                                                                'title',
                                                        hidden:
                                                            orderDirection !==
                                                                '' &&
                                                            orderDirection !==
                                                                'desc' &&
                                                            orderColumn ===
                                                                'title',
                                                    }"
                                                    >&darr;</span
                                                >
                                            </div>
                                        </div>
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 text-left">
                                        수정/삭제
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="post in posts.data" :key="post.id">
                                    <td class="px-6 py-4 text-sm">
                                        {{ post.created_at }}
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <div
                                            v-for="category in post.categories"
                                        >
                                            {{ category.name }}
                                        </div>
                                    </td>
                              <!--     <td class="px-6 py-4 text-sm">
                                        <img
                                            :src="post.original_image"
                                            alt="image"
                                            height="70"
                                        />
                                    </td>--> 
                                   <td class="px-6 py-4 text-sm text-overflow">
                                        {{ post.title }}
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <!-- v-if="can('post-edit')" -->
                                        <router-link
                                            :to="{
                                                name: 'posts.edit',
                                                params: { id: post.id },
                                            }"
                                            class="badge"
                                            > <div class="icon-edit-img">

                                            </div>
                                        </router-link>
                                        <!-- v-if="can('role.admin')" -->
                                        <a
                                            href="#"
                                            @click.prevent="deletePost(post.id)"
                                            class="ms-2 badge web_style"
                                            ><div class="icon-trash-img">

                                            </div></a
                                        >
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <Pagination
                        :data="posts"
                        :limit="3"
                        @pagination-change-page="
                            (page) =>
                                getPosts(
                                    page,
                                    search_category,
                                    search_id,
                                    search_title,
                                    search_content,
                                    search_global,
                                    orderColumn,
                                    orderDirection
                                )
                        "
                        class="mt-4"
                    />
                </div>
            </div>
</template>

<script setup>
import { ref, onMounted, watch } from "vue";
import usePosts from "@/composables/posts";
import useCategories from "@/composables/categories";
import { useAbility } from "@casl/vue";

const search_category = ref("");
const search_id = ref("");
const search_title = ref("");
const search_content = ref("");
const search_global = ref("");
const orderColumn = ref("created_at");
const orderDirection = ref("desc");
const { posts, getPosts, deletePost } = usePosts();
const { categoryList, getCategoryList } = useCategories();
const { can } = useAbility();
onMounted(() => {
    getPosts();
    getCategoryList();
});
const updateOrdering = (column) => {
    orderColumn.value = column;
    orderDirection.value = orderDirection.value === "asc" ? "desc" : "asc";
    getPosts(
        1,
        search_category.value,
        search_id.value,
        search_title.value,
        search_content.value,
        search_global.value,
        orderColumn.value,
        orderDirection.value
    );
};
watch(search_category, (current, previous) => {
    getPosts(
        1,
        current,
        search_id.value,
        search_title.value,
        search_content.value,
        search_global.value
    );
});
watch(search_id, (current, previous) => {
    getPosts(
        1,
        search_category.value,
        current,
        search_title.value,
        search_content.value,
        search_global.value
    );
});
watch(search_title, (current, previous) => {
    getPosts(
        1,
        search_category.value,
        search_id.value,
        current,
        search_content.value,
        search_global.value
    );
});
watch(search_content, (current, previous) => {
    getPosts(
        1,
        search_category.value,
        search_id.value,
        search_title.value,
        current,
        search_global.value
    );
});
watch(
    search_global,
    _.debounce((current, previous) => {
        getPosts(
            1,
            search_category.value,
            search_id.value,
            search_title.value,
            search_content.value,
            current
        );
    }, 200)
);
</script>
<style scoped>
.text-overflow{
  max-width: 130px; 
  overflow: hidden; 
  text-overflow: ellipsis;
  white-space: nowrap; 
}

@media screen and (max-width: 481px) 
{
.web_style{
    margin-left: 0rem !important;
}
}
@media screen and (max-width: 481px){
.o_table_mobile .tbl_basic table {
    width: 100%;
    min-width: 600px !important;
}
}
.select-option {
    width: 112px !important;
}
@media (max-width: 360px) {
  .responsive-flex-end {
    align-items: flex-end;
    flex-direction: column;
  }
}
</style>
