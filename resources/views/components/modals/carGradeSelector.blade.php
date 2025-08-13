<div x-data="carGradeSelector()">
    <p class="mb-3">차량의 등급을 선택해주세요.</p>
    
    <div class="list-group">
        <template x-for="grade in grades" :key="grade.gradeId">
            <button 
                class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                @click="selectGrade(grade)"
                style="cursor: pointer; text-align: left;"
            >
                <div class="flex-grow-1">
                    <h6 class="mb-1" x-text="grade.bpNm + ' ' + grade.gradeNm"></h6>
                </div>
                <div class="ms-2 align-self-center">
                    <i class="bi bi-chevron-right text-muted"></i>
                </div>
            </button>
        </template>
    </div>

    <!-- 선택 가능한 등급이 없는 경우 -->
    <div class="text-center mt-3" x-show="grades.length === 0">
        <p>선택 가능한 차량 등급이 없습니다.</p>
        <button class="btn btn-outline-danger btn-sm" @click="closeModal">닫기</button>
    </div>
</div>
