import Alpine from 'alpinejs';
import { addressbook } from '../../util/addressBook';
Alpine.store('addressbook', addressbook);

export default function () {
    return {
        isModify: false,
        form:{
            name: '',
            addr_post: '',
            addr1: '',
            addr2: '',
        },
        errors: {},
        init() {
            console.log(this.form);

            if(window.modalData.content.address) {
                this.form = window.modalData.content.address;
                this.isModify = true;
            }

        },
        submit() {
            console.log(this.form);
            Alpine.store('addressbook').storeContact(this.form).then(res => {
                console.log('addressbook add',res);
            });
        },
        modify() {
            console.log(this.form);
            Alpine.store('addressbook').updateContact(this.form.id, this.form).then(res => {
                console.log('addressbook modify',res);
            });
        },
    }
}