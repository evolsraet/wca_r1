<div class="py-4 p-2" id="nameChange" x-data="nameChange">

    <div class="mb-3">
        <div class="fw-bold fs-5">명의이전 서류첨부</div>
        <div class="text-muted small">명의이전 서류를 준비 하여 파일을 첨부 해 주세요.</div>
    </div>

    <form action="" method="post">
        <div class="mb-3">
            <x-forms.fileUpload
                name="file_auction_name_change"
                :errors="true"
                :preview="false"
            />
        </div>

        <div>
            <button class="btn btn-danger w-100 py-2 fw-semibold border-0" type="button" @click="nameChangeFileUpload">
                첨부하기
            </button>
        </div>
    </form>
</div>