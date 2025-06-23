export const addressbook = {
    // 주소록 조회
    getContacts: async (page = 1) => {
        try {
            const res = await Alpine.store('api').get('/api/addressbooks', {
                page: page
            });
            
            return res.data || res; 
        } catch (error) {
            console.error('주소록 조회 중 오류 발생:', error);
            throw error;
        }
    },

    // 주소록 등록 
    storeContact: async (contactData) => {
        try {
            const res = await Alpine.store('api').post('/api/addressbooks', {
                addressbook: contactData
            }).then(res => {

                if(res.status === 200) {

                    Alpine.store('swal').fire({
                        title: '주소록 등록 성공',
                        text: res.message,
                        icon: 'success',
                        confirmButtonText: '확인'
                    });

                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);

                    return res.data || res;

                } else {
                    throw new Error(res.message);
                }

            });

        } catch (error) {
            console.error('주소록 등록 중 오류 발생:', error);
            throw error;
        }
    },

    // 주소록 수정 
    updateContact: async (id, contactData) => {
        try {
            const res = await Alpine.store('api').put(`/api/addressbooks/${id}`, {
                addressbook: contactData
            }).then(res => {

                if(res.status === 200) {

                    Alpine.store('swal').fire({
                        title: '주소록 수정 성공',
                        text: res.message,
                        icon: 'success',
                        confirmButtonText: '확인'
                    });

                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);

                    return res.data || res;

                } else {
                    throw new Error(res.message);
                }

            });

        } catch (error) {
            console.error('주소록 수정 중 오류 발생:', error);
            throw error;
        }
    },

    // 주소록 삭제 
    deleteContact: async (id) => {
        try {
            const res = await Alpine.store('api').delete(`/api/addressbooks/${id}`).then(res => {

                if(res.status === 200) {

                    Alpine.store('swal').fire({
                        title: '주소록 삭제 성공',
                        text: res.message,
                        icon: 'success',
                        confirmButtonText: '확인'
                    });

                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);

                    return res.data || res;
                } else {
                    throw new Error(res.message);
                }
            });

        } catch (error) {
            console.error('주소록 삭제 중 오류 발생:', error);
            throw error;
        }
    }
}