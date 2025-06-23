import Alpine from 'alpinejs';
import { addressbook } from '../../util/addressbook';
Alpine.store('addressbook', addressbook);

export default function () {
    return {
        page: 1,
        perPage: 10,
        total: 0,
        list: [],

        init() {
            console.log('addressbookModal init');

            this.getContacts();
        },

        getContacts() {
            Alpine.store('addressbook').getContacts().then(res => {
                console.log('addressbook list',res);
                this.list = res.data;
            });
        },
        
        addAddress() {
            Alpine.store('modal').showHtmlFromUrl('/v2/components/modals/addressBookAdd', {
                title: '주소록 추가',
                size: 'modal-dialog-centered',
                showFooter: false,
            });

            Alpine.store('modal').close('addressBookModal');

        },

        modifyAddress(address) {
            console.log('modifyAddress',address);

            Alpine.store('modal').showHtmlFromUrl('/v2/components/modals/addressBookAdd', {
                title: '주소록 수정',
                size: 'modal-dialog-centered',
                showFooter: false,
            }, {
                content: {
                    address: address,
                },
            });

            Alpine.store('modal').close('addressBookModal');

        },

        deleteAddress(id) {
            console.log('deleteAddress',id);
            Alpine.store('swal').fire({
                title: '주소록 삭제',
                text: '주소록을 삭제하시겠습니까?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '삭제',
                cancelButtonText: '취소',
            }).then(result => {
                if (result.isConfirmed) {
                    Alpine.store('addressbook').deleteContact(id).then(res => {
                        console.log('deleteAddress',res);
                    });
                }
            });

        },

        selectAddress(address) {
            console.log('selectAddress',address);
            Alpine.store('modal').close('addressBookModal');
            // Alpine.store('modal').emit('addressBookSelect', address);
        },

    }
}