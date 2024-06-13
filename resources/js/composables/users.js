import { ref, inject } from 'vue'
import { useRouter } from 'vue-router'
import { cmmn } from '@/hooks/cmmn';

export default function useUsers() {
    const users = ref([])
    const user = ref({
        name: ''
    })
    const { callApi } = cmmn();
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
        swal({
            title: 'Are you sure?',
            text: 'You won\'t be able to revert this action!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            confirmButtonColor: '#ef4444',
            timer: 20000,
            timerProgressBar: true,
            reverseButtons: true
        })
            .then(result => {
                if (result.isConfirmed) {
                    axios.delete('/api/users/' + id)
                        .then(response => {
                            getUsers()
                            router.push({name: 'users.index'})
                            swal({
                                icon: 'success',
                                title: 'User deleted successfully'
                            })
                        })
                        .catch(error => {
                            swal({
                                icon: 'error',
                                title: 'Something went wrong'
                            })
                        })
                }
            })
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
