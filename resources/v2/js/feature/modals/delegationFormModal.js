export default function () {
    return {
        data: {
            carNumber: '',
            carOwnerName: '',
            carOwnerAddress: '',
            carOwnerId: '',
            formattedDate: '',
            user_phone: '',

            recipientName: '',
            recipientId: '',
            recipientAddress: '',
            carVin: ''
        },

        init() {
            const data = window.modalData.content;
            console.log(data);

            this.data.carNumber = data.carInfo.car_no;
            this.data.formattedDate = this.toDate();
            this.data.carOwnerName = data.auction.owner_name;
            this.data.carOwnerId = ''; // 필요 시 수정
            this.data.carOwnerAddress = `(${data.auction.addr_post}) ${data.auction.addr1} ${data.auction.addr2}`;
            this.data.user_phone = this.formatPhoneNumber(data.user_phone);
        },

        printDelegationForm() {
            this.syncInputsToDOM();

            const inputs = document.querySelectorAll('#printArea input');
            inputs.forEach(input => {
                input.setAttribute('value', input.value);
                input.style.border = 'none';
            });
            
            const printContents = document.getElementById('printArea').innerHTML;
            const originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;
            window.print();

            document.body.innerHTML = originalContents;
            location.reload();
        },

        syncInputsToDOM() {
            const setVal = (id, val) => {
                const el = document.getElementById(id);
                if (el) el.value = val;
            };

            setVal('recipient-name', this.data.recipientName);
            setVal('recipient-id', this.data.recipientId);
            setVal('recipient-address', this.data.recipientAddress);
            setVal('car-vin', this.data.carVin);
            setVal('delegator-id', this.data.carOwnerId);
        },

        formatPhoneNumber(phone) {
            if (!phone) return '';
            return phone.replace(/(\d{3})(\d{4})(\d{4})/, '$1-$2-$3');
        },

        toDate() {
            const today = new Date();
            const year = today.getFullYear();
            const month = String(today.getMonth() + 1).padStart(2, '0');
            const day = String(today.getDate()).padStart(2, '0');
            return `${year}년 ${month}월 ${day}일`;
        }
    };
}