<div x-data="addressBook()">
    <div class="list-group border rounded" style="max-height: 400px; overflow-y: auto;">
        <template x-for="address in list" :key="address.id">
            <div class="list-group-item list-group-item-action">
                <div class="d-flex justify-content-between align-items-start flex-md-row flex-column">
                    <!-- 왼쪽 주소 정보 -->
                    <div class="flex-grow-1 mb-2 mb-md-0 me-md-3">
                        <h6 class="mb-1" x-text="address.name"></h6>
                        <small x-text="address.addr_post + ' ' + address.addr1 + ' ' + address.addr2"></small>
                    </div>

                    <!-- 버튼 영역: PC에서는 가로 정렬, 모바일에서는 아래로 -->
                    <div class="d-flex gap-1 flex-wrap justify-content-end w-100 w-md-auto">
                        <button class="btn btn-outline-primary btn-sm" @click="selectAddress(address)">선택</button>
                        <button class="btn btn-outline-primary btn-sm" @click="modifyAddress(address)">수정</button>
                        <button class="btn btn-outline-primary btn-sm" @click="deleteAddress(address.id)">삭제</button>
                    </div>
                </div>
            </div>
        </template>
        
    </div>

    <div>
        <div class="d-flex justify-content-between align-items-center mt-3">
            <button 
                class="btn btn-outline-secondary btn-sm"
                :disabled="page === 1"
                @click="prevPage"
            >
                이전
            </button>
        
            <span class="text-muted small" x-text="`페이지 ${page} / ${lastPage}`"></span>
        
            <button 
                class="btn btn-outline-secondary btn-sm"
                :disabled="page === lastPage"
                @click="nextPage"
            >
                다음
            </button>
        </div>
    </div>

    <button class="btn btn-primary mt-3 w-100" @click="addAddress">추가하기</button>
</div>