<div x-data="{
    pdfUrl: window.modalData.content.pdfUrl,
    init() {
        console.log(this.pdfUrl);
    }
}">
    <div class="text-center">
        <iframe :src="pdfUrl" width="100%" height="600px"></iframe>
    </div>
</div>