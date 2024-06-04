<template>
    <div class="row justify-content-center my-2 p-3">
        <div class="col-md-12">
                  <!-- <router-link
                        v-if="can('role.admin')"
                        :to="{ name: 'users.create' }"
                        class="btn btn-primary btn-sm float-end"
                    >
                        Add New
                    </router-link>
--> 
                </div>
                <div class="search-type2 mb-2">
                        <div class="border-xsl">
                        <div class="image-icon-excel">

                        </div>
                    </div>
                    <input type="text" placeholder="회원 검색" v-model="search_global" style="width: auto !important;">
                            <button type="button" class="search-btn">검색</button>
                        </div>
                    <div class="container mb-3">
                    <div class="d-flex justify-content-end responsive-flex-end">
                        <div class="text-start status-selector">
                        <input type="radio" name="status" value="all" id="all" hidden checked @change="setFilter('all')">
                        <label for="all" class="mx-2">전체</label>

                        <input type="radio" name="status" value="ing" id="ongoing" hidden @change="setFilter('ing')">
                        <label for="ongoing">일반</label>

                        <input type="radio" name="status" value="done" id="completed" hidden @change="setFilter('done')">
                        <label for="completed" class="mx-2">딜러</label>
                    </div>

                        <div class="text-end select-option">
                            <select class="form-select select-rank" aria-label="심사중">
                                <option selected>심사중</option>
                                <option value="1">정상</option>
                                <option value="2">탈퇴</option>
                                <option value="3">가입거부</option>
                            </select>
                        </div>
                    </div>
                    </div>
                    <div class="o_table_mobile my-5">
                    <div class="tbl_basic tbl_dealer">
                        <table class="table">
                            <thead>
                       <!--         <tr>
                                    <th class="px-6 py-3 bg-gray-50 text-left">
                                        <input
                                            v-model="search_id"
                                            type="text"
                                            class="inline-block mt-1 form-control"
                                            placeholder="Filter by ID"
                                        />
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 text-left">
                                        <input
                                            v-model="search_title"
                                            type="text"
                                            class="inline-block mt-1 form-control"
                                            placeholder="Filter by Title"
                                        />
                                    </th>
                                    <th class="px-6 py-3 text-start"></th>
                                    <th class="px-6 py-3 text-start"></th>
                                </tr>-->
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 text-left">
                                        <div
                                            class="flex flex-row items-center justify-between cursor-pointer justify-content-center"
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
                                                가입일
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
                                    <th class="px-6 py-3 ">
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
                                                회원명/상태
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
                                    <th class="px-6 py-3 ">
                                        <div
                                            class="flex flex-row justify-content-center"
                                            @click="updateOrdering('email')"
                                        >
                                            <div
                                                class="font-medium text-uppercase"
                                                :class="{
                                                    'font-bold text-blue-600':
                                                        orderColumn === 'email',
                                                }"
                                            >
                                                이메일
                                            </div>
                                            <div class="select-none">
                                                <span
                                                    :class="{
                                                        'text-blue-600':
                                                            orderDirection ===
                                                                'asc' &&
                                                            orderColumn ===
                                                                'email',
                                                        hidden:
                                                            orderDirection !==
                                                                '' &&
                                                            orderDirection !==
                                                                'asc' &&
                                                            orderColumn ===
                                                                'email',
                                                    }"
                                                    >&uarr;</span
                                                >
                                                <span
                                                    :class="{
                                                        'text-blue-600':
                                                            orderDirection ===
                                                                'desc' &&
                                                            orderColumn ===
                                                                'email',
                                                        hidden:
                                                            orderDirection !==
                                                                '' &&
                                                            orderDirection !==
                                                                'desc' &&
                                                            orderColumn ===
                                                                'email',
                                                    }"
                                                    >&darr;</span
                                                >
                                            </div>
                                        </div>
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 text-left">
                                        회원
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 text-left">
                                        수정/삭제
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="post in users.data" :key="post.id">
                                    <td class="px-6 py-4 text-sm">
                                        {{ post.created_at }}
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        {{ post.name }}    
                                    <div :class="{'blue-box ms-2': post.status === 'ok', 'blue-box02 ms-2': post.status === 'ask'}">
                                    {{ post.status === 'ok' ? '정상' : post.status === 'ask' ? '심사중' : '' }}
                                    </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        {{ post.email }}
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        {{ post.roles.includes('dealer') ? '딜러' : '일반' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <router-link
                                            v-if="can('role.admin')"
                                            :to="{
                                                name: 'users.edit',
                                                params: { id: post.id },
                                            }"
                                            class="badge bg-primary tc-wh"
                                            >수정
                                        </router-link>
                                        <a
                                            href="#"
                                            v-if="can('role.admin')"
                                            @click.prevent="deleteUser(post.id)"
                                            class="ms-2 badge bg-danger tc-wh"
                                            >삭제</a
                                        >
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
                <div class="card-footer">
                    <Pagination 
                        :data="users"
                        :limit="3"
                        @pagination-change-page="
                            (page) =>
                                getUsers(
                                    page,
                                    search_id,
                                    search_title,
                                    search_global,
                                    orderColumn,
                                    orderDirection
                                )
                        "
                        class="mt-4 justify-content-center"
                    />
                </div>

</template>

<script setup>
import { ref, onMounted, watch } from "vue";
import useUsers from "../../../composables/users";
import { useAbility } from "@casl/vue";

const search_id = ref("");
const search_title = ref("");
const search_global = ref("");
const orderColumn = ref("created_at");
const orderDirection = ref("desc");
const { users, getUsers, deleteUser } = useUsers();
const { can } = useAbility();
onMounted(() => {
    getUsers();
});
const updateOrdering = (column) => {
    orderColumn.value = column;
    orderDirection.value = orderDirection.value === "asc" ? "desc" : "asc";
    getUsers(
        1,
        search_id.value,
        search_title.value,
        search_global.value,
        orderColumn.value,
        orderDirection.value
    );
};
watch(search_id, (current, previous) => {
    getUsers(1, current, search_title.value, search_global.value);
});
watch(search_title, (current, previous) => {
    getUsers(1, search_id.value, current, search_global.value);
});
watch(
    search_global,
    _.debounce((current, previous) => {
        getUsers(1, search_id.value, search_title.value, current);
    }, 200)
);
</script>
<style scoped>
.sidebar {
    height: 116vh !important;
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