export default function () {
    return {
        init() {
            console.log('auctionConfirmationDoc init');
        },
        printDoc() {
            console.log('printDoc');
            
            const printContents = document.getElementById('printArea').innerHTML;
            const originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;
            window.print();

            document.body.innerHTML = originalContents;
            location.reload();

        }
    }
}