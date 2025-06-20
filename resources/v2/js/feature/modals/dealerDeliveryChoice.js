import { appendFormData, appendFilesToFormData, setupFileUploadListeners } from '../../util/fileUpload';

export default function () {
    return {
        bid: null,
        loading: true,
        data: {},
        form: {
            auction: {}
        },
        errors: {},
        init() {

            this.data = window.modalData.content.data;

            const auction = window.modalData.content.auction;

            console.log('dealerDeliveryChoice init', this.data);
            this.form.auction = auction;
            console.log('this.form.auction', this.form.auction);
        },
        bankSelector() {
            Alpine.store(`modal`).showHtmlFromUrl('/v2/components/modals/bankSelectorModal', {
                id: 'bankSelectorModal',
                title: '은행 선택',
                showFooter: false
            });
        }
    };
}