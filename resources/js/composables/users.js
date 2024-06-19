import { ref, inject } from 'vue'
import { useRouter } from 'vue-router'
import { cmmn } from '@/hooks/cmmn';

export default function useUsers() {
    const users = ref([])
    const user = ref({
        name: ''
    })
    const { callApi , wica , wicac} = cmmn();
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
        column = '',
        direction = ''
    ) => {
        const apiList = [];
        if(stat != 'all'){
            apiList.push(`users.status:${stat}`)
        } 
        if(role != 'all'){
            apiList.push(`users.roles:${role}`)
        }
        console.log(apiList);

        return wicac.conn()
        .url(`/api/users`)
        .where(apiList)
        .order([
            [`${column}`,`${direction}`]
        ])
        .page(`${page}`)
        .callback(function(result) {
            console.log('wicac.conn callback ' , result);
            users.value = result.data;
            pagination.value = result.rawData.data.meta;
            return result.data;
        })
        .get();

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

    const updateUser = async (user,id) => {
        const form = {
            user
        }
        //console.log(JSON.stringify(form));
        if (isLoading.value) return;
        wica.ntcn(swal)
        .title('수정하시겠습니까?') // 알림 제목
        .icon('Q') //E:error , W:warning , I:info , Q:question
        .callback(function(result) {
            if(result.isOk){
                //console.log(result);
                axios.put('/api/users/' + id, form)
                    .then(response => {
                        wica.ntcn(swal)
                        .icon('I') //E:error , W:warning , I:info , Q:question
                        .callback(function(result) {
                            if(result.isOk){                                
                                router.push({name: 'users.index'});                    
                            }
                        })
                        .alert('회원정보가 정상적으로 수정되었습니다.');
                    })
                    .catch(error => {
                        if (error.response?.data) {
                            validationErrors.value = error.response.data.errors
                        }
                    })
                    .finally(() => isLoading.value = false)
            }
        }).confirm();
           
    }

    const deleteUser = async (id) => {
        wica.ntcn(swal)
        .param({ _id : id }) // 리턴값에 전달 할 데이터
        .title('삭제하시겠습니까?') // 알림 제목
        .icon('W') //E:error , W:warning , I:info , Q:question
        .callback(function(result) {
            if(result.isOk){
                console.log(result);
                axios.delete('/api/users/' + id)
                    .then(response => {
                        wica.ntcn(swal)
                        .icon('I') //E:error , W:warning , I:info , Q:question
                        .callback(function(result) {
                            if(result.isOk){                                
                                location.reload();                          
                            }
                        })
                        .alert('이용후기가 정상적으로 삭제되었습니다.');
                    })
                    .catch(error => {
                        wica.ntcn(swal)
                        .title('오류가 발생하였습니다.')
                        .icon('E') //E:error , W:warning , I:info , Q:question
                        .alert('관리자에게 문의해주세요.');
                    })
            }
        })
        .confirm('삭제된 정보는 복구할 수 없습니다.');   
        
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
