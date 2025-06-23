import Alpine from 'alpinejs';
import { addressbook } from '../../util/addressbook';
Alpine.store('addressbook', addressbook);

export default function () {
    return {
        addressbook: Alpine.store('addressbook'),
        init() {
            console.log('addressbookModal init');

            this.getContacts();
        },

        getContacts() {
            Alpine.store('addressbook').getContacts().then(res => {
                console.log('addressbook list',res);
            });
        },
    }
}