<template>
    <div class="p-3 row justify-content-center my-2">
        <router-link
            :to="{ name: 'addr.create' }"
            class="border-red-write"
        >
            <div class="plus-Icon-wh"></div>
        </router-link>
        <div class="col-md-12"></div>
        <div class="search-type2 mb-5">
            <div class="border-xsl">
                <div class="image-icon-excel"></div>
            </div>
            <input type="text" placeholder="명칭 또는 주소 입력" style="width: auto !important;">
            <button type="button" class="search-btn">검색</button>
        </div>
        <div class="container mb-3"></div>
        <div class="o_table_mobile my-5">
            <div class="tbl_basic tbl_dealer">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-50 justify-content-center">
                                <div class="flex flex-row items-center justify-content-center justify-between cursor-pointer">
                                    <div class="leading-4 font-medium text-gray-500 uppercase tracking-wider">등록일</div>
                                </div>
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-left">
                                <span class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">지점</span>
                            </th>
                            <th class="px-6 py-3 text-left">
                                <div class="flex flex-row justify-content-center">
                                    <div class="font-medium text-uppercase">주소</div>
                                </div>
                            </th>
                            <th class="px-6 py-3 text-left">
                                <div class="flex flex-row justify-content-center">
                                    <div class="font-medium text-uppercase">메모</div>
                                </div>
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-left">수정/삭제</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="px-6 pb-3 pt-1 text-sm">등록일</td>
                            <td class="px-6 pb-3 pt-1 text-sm">지점</td>
                            <td class="px-6 pb-3 pt-1 text-sm text-overflow">주소 데이터</td>
                            <td class="px-6 pb-3 pt-1 text-sm text-overflow">메모</td>
                            <td class="px-6 pb-3 pt-1 text-sm">
                                <a href="#" class="ms-2 badge web_style">
                                    <div class="icon-edit-img"></div>
                                </a>
                                <a href="#" class="ms-2 badge web_style">
                                    <div class="icon-trash-img"></div>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<style scoped>
.search-type2 .search-btn {
    right: 13px;
    top: 63px !important;
}
.text-overflow {
    max-width: 130px; 
    overflow: hidden; 
    text-overflow: ellipsis;
    white-space: nowrap; 
}

@media screen and (max-width: 481px) {
    .web_style {
        margin-left: 0rem !important;
    }
}

@media screen and (max-width: 481px) {
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
