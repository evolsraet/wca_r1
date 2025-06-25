<div class="mt-3">
    <button class="btn btn-outline-primary w-100 mb-3"
    @click="
        Alpine.store(`modal`).showHtmlFromUrl(`/v2/components/modals/diagResultDoc`, 
        {title: `위카 진단평가 결과`, size: `modal-xl modal-dialog-centered`, footerButtons: [{text: `닫기`, class: `btn-secondary`, dismiss: true}]},
        {
            content: {
                pdfUrl: diag?.extra?.url_pdf,
            }
        })
    "
    >
        <i class="mdi mdi-file me-2"></i>
        위카 진단평가 확인하기
    </button>

    {{-- 사업자등록증 추가 --}}
    
    {{-- 명의이전등록증확인 추가 --}}

</div>