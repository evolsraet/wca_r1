<div x-data="{
    pdfUrl: window.modalData.content.pdfUrl,
    init() {
        console.log(this.pdfUrl);
    }
}">
    <div class="text-center">
        <div x-show="pdfUrl">
            <iframe :src="pdfUrl" width="100%" height="600px"></iframe>
        </div>
        <div x-show="!pdfUrl">
            <div class="py-5 text-center">
                <p>파일이 없습니다.</p>
            </div>
        </div>
    </div>
</div>