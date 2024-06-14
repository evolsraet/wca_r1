import { ref, inject } from 'vue'
import { useRouter } from 'vue-router'
import { cmmn } from '@/hooks/cmmn';

export default function useUsers() {
    const users = ref([])
    const user = ref({
        name: ''
    })
    const { callApi , salert } = cmmn();
    const router = useRouter()
    const validationErrors = ref({})
    const isLoading = ref(false)
    const swal = inject('$swal')
    const pagination = ref({});
    
    const getUsers = async (
        page = 1,
        search_id = '',
        search_title = '',
        search_global = '',
        order_column = 'created_at',
        order_direction = 'desc'
    ) => {
        axios.get('/api/users?page=' + page +
            '&search_id=' + search_id +
            '&search_title=' + search_title +
            '&search_global=' + search_global +
            '&order_column=' + order_column +
            '&order_direction=' + order_direction)
            .then(response => {
                users.value = response.data;
                console.log(users.value);
            })
   
    }
    
    const adminGetUsers = async (
        page = 1,
        stat = 'all',
        role = 'all',
    ) => {
        const apiList = [];
        if(stat != 'all'){
            apiList.push(`users.status:${stat}`)
        } 
        if(role != 'all'){
            apiList.push(`users.roles:${role}`)
        }

        console.log(apiList);
        return callApi({
            _type: 'get',
            _url:`/api/users`,
            _param: {
                _where:apiList,
                _page:`${page}`
            }
        }).then(async result => {
            users.value = result.data;
            pagination.value = result.rawData.data.meta;
        });

    }

    const getUser = async (id) => {
        try {
            const response = await axios.get('/api/users/' + id);
            return response.data.data;
        } catch (error) {
            throw error;
        }
    }
    

    const storeUser = async (user) => {
        if (isLoading.value) return;

        isLoading.value = true
        validationErrors.value = {}

        let serializedPost = new FormData()
        for (let item in user) {
            if (user.hasOwnProperty(item)) {
                serializedPost.append(item, user[item])
            }
        }

        axios.post('/api/users', serializedPost)
            .then(response => {
                router.push({name: 'users.index'})
                swal({
                    icon: 'success',
                    title: 'User saved successfully'
                })
            })
            .catch(error => {
                if (error.response?.data) {
                    validationErrors.value = error.response.data.errors
                }
            })
            .finally(() => isLoading.value = false)
    }

    const updateUser = async (user) => {
        console.log(JSON.stringify(user));
        if (isLoading.value) return;

        isLoading.value = true
        validationErrors.value = {}

        axios.put('/api/users/' + user.id, user)
            .then(response => {
                router.push({name: 'users.index'})
                swal({
                    icon: 'success',
                    title: 'User updated successfully'
                })
            })
            .catch(error => {
                if (error.response?.data) {
                    validationErrors.value = error.response.data.errors
                }
            })
            .finally(() => isLoading.value = false)
    }

    const deleteUser = async (id) => {
        salert({
            _swal: swal, //필수 지정
            _title: '삭제하시겠습니까?',
            _msg: '삭제된 정보는 복구할 수 없습니다.',
            _type: 'C',
            _isHtml: true, 
            _icon: 'Q',
        },function(result){
            if(result.isOk){
                axios.delete('/api/users/' + id)
                    .then(response => {
                        salert({
                            _type: 'A',
                            _swal: swal, //필수 지정
                            _msg: '삭제되었습니다.',
                            _icon: 'I',
                            _isHtml: true, //_msg가 HTML 태그 인 경우 활성화
                        },function(result){
                            if(result.isOk){
                                //location.reload();
                                router.push({name: 'users.index'})
                                getUsers()
                                
                            }
                        });
                    })
                    .catch(error => {
                        salert({
                            _type: 'A',
                            _swal: swal, //필수 지정
                            _title: '오류가 발생하였습니다.',
                            _msg: '관리자에게 문의해주세요.',
                            _icon: 'E',
                            _isHtml: true, //_msg가 HTML 태그 인 경우 활성화

                        },function(result){
                            console.log(result);
                        })
                    })
            }
            //console.log('salert', result);
        }); 
        
    }

    return {
        users,
        user,
        getUsers,
        getUser,
        adminGetUsers,
        storeUser,
        updateUser,
        deleteUser,
        validationErrors,
        isLoading,
        pagination
    }
}
