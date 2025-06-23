import Alpine from 'alpinejs';
import { addressbook } from '../../util/addressbook';
Alpine.store('addressbook', addressbook);

export default function () {
    return {
        page: 1,
        lastPage: 1,
        total: 0,
        list: [],

        init() {
            console.log('addressbookModal init');

            this.getContacts();
        },

        prevPage() {
            if (this.page > 1) {
                this.getContacts(this.page - 1);
            }
        },
        nextPage() {
            if (this.page < this.lastPage) {
                this.getContacts(this.page + 1);
            }
        },

        getContacts(page = 1) {
            this.page = page;
            Alpine.store('addressbook').getContacts(page).then(res => {
                console.log('addressbook list', res);
                this.list = res.data;
                this.total = res.meta.total;
                this.lastPage = res.meta.last_page;
                this.page = res.meta.current_page;
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


            // 부모 컴포넌트로 이벤트 전달
            window.dispatchEvent(new CustomEvent('addressBookSelect', {
                detail: {
                    address: address
                }
            }));

            
            Alpine.store('modal').close('addressBookModal');
            // Alpine.store('modal').emit('addressBookSelect', address);
        },

    }
}