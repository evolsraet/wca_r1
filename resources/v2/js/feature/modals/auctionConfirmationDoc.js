export default function () {
    return {
        auction: {},
        diag: {},
        init() {
            this.auction = window.modalData.content.auction;
            this.diag = window.modalData.content.diag;
        },
        printDoc() {
            const printContents = document.getElementById('printArea').innerHTML;
            const originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;
            window.print();

            document.body.innerHTML = originalContents;
            location.reload();

        }
    }
}