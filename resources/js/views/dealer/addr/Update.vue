<template>
    <div class="container mov-wide py-4">
    <h4>탁송 주소 관리</h4>
    <div class="row justify-content-center my-5">
        <div class="col-md-10">
            <div class="card border-0 shadow-none">
                <div class="card-body">
                    <form>
                        <div class="mb-3">
                            <label for="user-title" class="form-label">명칭</label>
                            <input type="text" class="form-control" placeholder="예 ) 대전점" />
                            <div class="text-danger mt-1">
                                <!-- Error messages -->
                            </div>
                            <div class="form-group my-4">
                                <label for="user-title" class="form-label">소속상사 주소</label>
                                <input type="text" class="form-control" placeholder="주소를 입력하세요" readonly />
                                <div>
                                    <input type="text" class="form-control" placeholder="주소" readonly />
                                </div>
                                <input type="text" class="form-control" placeholder="상세주소" />
                                <div style="display: none; border: 1px solid; width: 100%; height: 466px; margin: 5px 0px; position: relative">
                                </div>
                            </div>
                            <div class="my-2">
                                <label for="user-title" class="form-label">메모</label>
                                <textarea class="form-control no-resize mt-2" style="height: 100px; overflow-y: hidden;" placeholder="메모 입력"></textarea>
                            </div>
                        </div>
                        <div class="my-4">
                            <button class="w-100 btn btn-primary">
                                <span>수정</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<style scoped>
h4 {
    position: relative;
    padding-bottom: 15px;
}

h4::after {
    content: "";
    display: block;
    width: 100%;
    height: 2px; 
    background-color: #f7f8fb;
    position: absolute;
    bottom: 0;
    left: 0;
}
</style>
