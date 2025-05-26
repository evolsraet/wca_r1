import { ref, inject } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';
import { cmmn } from '@/hooks/cmmn';

export function initAddressBookSystem() {
    const contacts = ref([]);
    const contact = ref({
        name: '',
        addr_post: '',
        addr1: '',
        addr2: '',
        user_id: ''
    });
    const pagination = ref({});
    const router = useRouter();
    const validationErrors = ref({});
    const isLoading = ref(false);
    const swal = inject('$swal');
    const { wicac, wica } = cmmn();

    const getContacts = async (page = 1) => {
        return wicac.conn()
            .url(`/api/addressbooks`)
            .page(`${page}`)
            .callback(function(result) {
                contacts.value = result.data;
                pagination.value = result.rawData.data.meta;
                console.log("Pagination: ", pagination.value);
                return result.data;
            })
            .get();
    };

    const getContact = async (id) => {
        isLoading.value = true;
        try {
            const response = await axios.get(`/api/addressbooks/${id}`);
            contact.value = response.data.data;
        } catch (error) {
            console.error(error);
            throw error;
        } finally {
            isLoading.value = false;
        }
    };

    const storeContact = async (contactData) => {
        console.log('Sending Data:', JSON.stringify(contactData, null, 2)); // 요청 데이터 로그 추가
        try {
            const response = await axios.post(`/api/addressbooks`, { addressbook: contactData });
            console.log('Response Data:', response.data); // 응답 데이터 로그 추가
            return response.data;
        } catch (error) {
            if (error.response?.data) {
                validationErrors.value = error.response.data.errors;
                console.error('Validation Errors:', validationErrors.value); // 검증 오류 로그 추가
            }
            throw error;
        }
    };

    const updateContact = async (id, contactData) => {
        try {
            const response = await axios.put(`/api/addressbooks/${id}`, { addressbook: contactData });
            return response.data;
        } catch (error) {
            if (error.response?.data) {
                validationErrors.value = error.response.data.errors;
            }
            throw error;
        }
    };

    const deleteContact = async (id) => {
        try {
            wica.ntcn(swal)
                .param({ _id: id })
                .title('Are you sure you want to delete this contact?')
                .icon('W')
                .callback(async function(result) {
                    if (result.isOk) {
                        const response = await axios.delete(`/api/addressbooks/${id}`);
                        wica.ntcn(swal)
                            .addClassNm('cmm-review-custom')
                            .icon('I')
                            .alert('Contact has been successfully deleted.');
                        getContacts();
                    }
                })
                .confirm('Deleted information cannot be recovered.');
        } catch (error) {
            console.error('Error deleting contact:', error);
            wica.ntcn(swal)
                .title('An error occurred.')
                .icon('E')
                .alert('Please contact the administrator.');
        }
    };

    return {
        contacts,
        contact,
        getContacts,
        getContact,
        storeContact,
        updateContact,
        deleteContact,
        validationErrors,
        isLoading,
        pagination
    };
}
