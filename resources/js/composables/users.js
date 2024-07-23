import { ref, reactive, inject } from 'vue'
import { useRouter } from 'vue-router'
import { cmmn } from '@/hooks/cmmn';

export default function useUsers() {
    const processing = ref(false);
    const users = ref([])
    const user = ref({
        name: ''
    })
    const { wica , wicac} = cmmn();
    const router = useRouter()
    const validationErrors = ref({})
    const isLoading = ref(false)
    const swal = inject('$swal')
    const pagination = ref({});
    
    const editForm = reactive({
        name:"",
        status:"",
        role:"",
        company:"",
        company_post:"",
        company_addr1:"",
        company_addr2:"",
        introduce:"",
        receive_post:"",
        receive_addr1:"",
        receive_addr2:"",
        receive_addr2:"",
        file_user_photo:"",
        file_user_photo_name:"",
        file_user_biz:"",
        file_user_sign:"",
        file_user_sign_name:"",
        file_user_cert:"",
        file_user_cert_name:"",
    
    });

    const adminEditForm = reactive({
        name:"",
        phone:"",
        currentPw:"",
        password:"",
        password_confirmation:"",
    });

    const getUserStatus = async(
        stat = 'all'
    )=>{
        const apiList = [];
        if(stat != 'all'){
            apiList.push(`users.status:${stat}`)
        }

        return wicac.conn()
        .url(`/api/users`)
        .where(apiList)
        .callback(function(result) {
            return result.page.total;
        })
        .get();
    }
    
    const adminGetUsers = async (
        page = 1,
        stat = 'all',
        role = 'all',
        column = '',
        direction = '',
        search_title = ''
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
        .log()
        .where(apiList)
        .order([
            [`${column}`,`${direction}`]
        ])
        .page(`${page}`)
        .search(search_title)
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
            return wicac.conn()
            .url(`/api/users/${id}`)
            .log()
            .callback(function(result) {
                user.value = result.data;
                return result.data;
            })
            .get();
        } catch (error) {
            throw error;
        }
    }

    const updateUser = async (editForm, id) => {
        console.log(JSON.stringify(editForm));
        if (isLoading.value) return;
        let payload = {
            user: {
                name: editForm.name,
                status: editForm.status,
                role: editForm.role
            },
            dealer: {
                name: editForm.dealer_name,
                company: editForm.company,
                company_post: editForm.company_post,
                company_addr1: editForm.company_addr1,
                company_addr2: editForm.company_addr2,
                introduce: editForm.introduce,
                company_duty: editForm.dealerCompanyDuty,
                receive_post: editForm.receive_post,
                receive_addr1: editForm.receive_addr1,
                receive_addr2: editForm.receive_addr2,
            }
        };
    
        console.log(JSON.stringify(payload));
        const formData = new FormData();
        formData.append('user', JSON.stringify(payload.user));
        if(editForm.role == "dealer"){
            formData.append('dealer', JSON.stringify(payload.dealer));
        }
        //formData.append('_method', 'PUT');
        
        if (editForm.file_user_photo) {
            formData.append('file_user_photo', editForm.file_user_photo);
        }
        if (editForm.file_user_biz) {
            formData.append('file_user_biz', editForm.file_user_biz);
        }
        if (editForm.file_user_cert) {
            formData.append('file_user_cert', editForm.file_user_cert);
        }
        if (editForm.file_user_sign) {
            formData.append('file_user_sign', editForm.file_user_sign);
        }
        for (const x of formData) {
            console.log(x);
        };

        wica.ntcn(swal)
            .title('수정하시겠습니까?') // 알림 제목
            .icon('Q') //E:error , W:warning , I:info , Q:question
            .callback(async function(result) {
                if (result.isOk) {
                    wicac.conn()
                    .url(`/api/users/${id}`)
                    .param(formData)
                    .multipartUpdate()
                    .callback(async function(result) {
                        console.log('wicac.conn callback ' , result);
                        if(result.isError) {
                            validationErrors.value = result.msg;
                            wica.ntcn(swal)
                            .title('변경 실패')
                            .icon('E') //E:error , W:warning , I:info , Q:question
                            .alert('회원정보 변경에 실패하였습니다.');
                        } else {
                            wica.ntcn(swal).icon('S').title('정상 처리 되었습니다.').fire();
                            //console.log(response);
                            await router.push({ name: "users.index" });
                        }
                    })
                    .post();
                }
            }).confirm();
    };

    const adminStoreUser = async (profileData) => {
        if (isLoading.value) return;
        let payload = {
            user: {
                name: profileData.name,
                email: profileData.email,
                status: 'ok',
                password:profileData.password,
                password_confirmation: profileData.password_confirmation,
                phone: profileData.phone,
                role: profileData.role
            },
            dealer: {
                name:profileData.dealer_name,
                phone:profileData.phone,
                birthday:profileData.dealerBirthDate,
                company:profileData.company,
                company_duty:profileData.dealerCompanyDuty,
                company_post:profileData.company_post,
                company_addr1:profileData.company_addr1,
                company_addr2:profileData.company_addr2,
                introduce:profileData.introduce,
                receive_post:profileData.receive_post,
                receive_addr1:profileData.receive_addr1,
                receive_addr2:profileData.receive_addr2
            }
        };
        
        const formData = new FormData();
        formData.append('user', JSON.stringify(payload.user));
        if(profileData.role == "dealer"){
            formData.append('dealer', JSON.stringify(payload.dealer));
        }
        if (profileData.file_user_photo) {
            formData.append('file_user_photo', profileData.file_user_photo);
        }
        if (profileData.file_user_biz) {
            formData.append('file_user_biz', profileData.file_user_biz);
        }
        if (profileData.file_user_cert) {
            formData.append('file_user_cert', profileData.file_user_cert);
        }
        if (profileData.file_user_sign) {
            formData.append('file_user_sign', profileData.file_user_sign);
        }
        for (const x of formData) {
            console.log(x);
        };

        wica.ntcn(swal)
            .title('등록하시겠습니까?') // 알림 제목
            .icon('Q') //E:error , W:warning , I:info , Q:question
            .callback(async function(result) {
                if (result.isOk) {
                    wicac.conn()
                    .url(`/api/users`)
                    .param(formData)
                    .multipart()
                    .callback(async function(result) {
                        //console.log('wicac.conn callback ' , result);
                        if(result.isError) {
                            validationErrors.value = result.msg;
                        } else {
                            wica.ntcn(swal).icon('S').title('정상 처리 되었습니다.').fire();
                            //console.log(response);
                            await router.push({ name: "users.index" });
                        }
                    })
                    .post();
                }
            }).confirm();
            
    };

    const updateMyInfo = async(adminEditForm,id) =>{
        validationErrors.value = {};
        if (isLoading.value) return;

        wica.ntcn(swal)
        .title('수정하시겠습니까?') // 알림 제목
        .icon('Q') //E:error , W:warning , I:info , Q:question
        .callback(async function(result) {
            if(result.isOk){
                wicac.conn()
                .url(`/api/users/confirmPassword`)
                .param({
                    'password' : adminEditForm.currentPw
                })
                .callback(function(result) {
                    if(result.isSuccess){
                        //내 정보 변경 진행
                        myInfoModify(adminEditForm,id);
                    }else{
                        wica.ntcn(swal)
                        .title('비밀번호 불일치')
                        .icon('E') //E:error , W:warning , I:info , Q:question
                        .alert('비밀번호가 옳바르지 않습니다.');
                    }
                })
                .post();
            }
        }).confirm();
    };

    const myInfoModify = async(adminEditForm,id) =>{
        let jsonData = {};
        if(adminEditForm.password || adminEditForm.password_confirmation){
            jsonData = {
                user:{
                    name: adminEditForm.name,
                    phone: adminEditForm.phone,
                    password: adminEditForm.password,
                    password_confirmation : adminEditForm.password_confirmation
                }
            }
        }else{
            jsonData = {
                user:{
                    name: adminEditForm.name,
                    phone: adminEditForm.phone
                }
            }
        }

        wicac.conn()
        .url(`/api/users/${id}`)
        .param(jsonData)
        .callback(function(result) {
            if(result.isSuccess){
                //내 정보 변경 진행
                wica.ntcn(swal)
                .icon('I') //E:error , W:warning , I:info , Q:question
                .callback(function(result) {
                    if (result.isOk) {
                        router.push({ name: 'users.index' });
                    }
                })
                .alert('내 정보가 정상적으로 수정되었습니다.');
            }else{
                validationErrors.value = result.msg;
                wica.ntcn(swal)
                .title('변경 실패')
                .icon('E') //E:error , W:warning , I:info , Q:question
                .alert('내 정보 변경에 실패하였습니다.');
            }
        })
        .put();
    }

    const fileUserSignUpload = async (id,fileData) =>{
        const formData = new FormData();
        if (fileData.file_user_sign) {
            formData.append('file_user_sign', fileData.file_user_sign);
        }

        return wicac.conn()
        .url(`/api/users/${id}`)
        .param(formData)
        .multipartUpdate()
        .callback(async function(result) {
            return result.isSuccess;
        })
        .post();

    }

    const setRegisterUser = async(profileData) => {
        if (processing.value) return;

        processing.value = true;
        validationErrors.value = {};

        let payload = {
            user: {
                name: profileData.name,
                email: profileData.email,
                phone: profileData.phone,
                password: profileData.password,
                password_confirmation: profileData.password_confirmation,
            }
        };
        if (profileData.isDealer) {
            payload.dealer ={
                name: profileData.dealer_name,
                phone: profileData.dealerContact,
                birthday: profileData.dealerBirthDate,
                company: profileData.company,
                company_duty: profileData.dealerCompanyDuty,
                company_post: profileData.company_post,
                company_addr1: profileData.company_addr1,
                company_addr2: profileData.company_addr2,
                receive_post: profileData.receive_post,
                receive_addr1: profileData.receive_addr1,
                receive_addr2: profileData.receive_addr2,
                introduce: profileData.introduce,
            }
            payload.user.role = 'dealer'; 
        } else {
            payload.user.role = 'user';
        } 
        console.log("전체 데이터:" , payload.user.role);
        console.log("역할:" , payload);
        const formData = new FormData();
        
        formData.append('user',JSON.stringify(payload.user));
        formData.append('dealer',JSON.stringify(payload.dealer));

        if(profileData.file_user_photo){
            formData.append('file_user_photo', profileData.file_user_photo);
        }
        if(profileData.file_user_biz){
            formData.append('file_user_biz', profileData.file_user_biz);
        }
        if(profileData.file_user_cert){
            formData.append('file_user_cert', profileData.file_user_cert);
        }
        if(profileData.file_user_sign){
            formData.append('file_user_sign', profileData.file_user_sign);
        }
        for (const x of formData) {
            console.log(x);
        };
        wicac.conn()
        .url(`/api/users`)
        .param(formData)
        .multipart()            
        .callback(async function(result) {
            console.log('wicac.conn callback ' , result);
            if(result.isError) {
                processing.value = false;
                validationErrors.value = result.msg;
                wica.ntcn(swal)
                .title('가입 실패')
                .icon('E') //E:error , W:warning , I:info , Q:question
                .alert('회원가입에 실패하였습니다.');
            } else {
                processing.value = false;
                wica.ntcn(swal)
                .icon('I') //E:error , W:warning , I:info , Q:question
                .callback(function(result) {
                    if (result.isOk) {
                        router.push({ name: "auth.login" });
                    }
                })
                .alert('회원가입이 완료되었습니다');
               
                
            }
        })
        .post();
    }

    const updateProfile = async (profile,userId) => {
        let payload = {
            user : {
              name : profile.value.name,    
            },
            dealer: {
                name: profile.value.dealer_name,
                company: profile.value.company,
                company_post: profile.value.company_post,
                company_addr1:profile.value.company_addr1,
                company_addr2: profile.value.company_addr2,
                introduce: profile.value.introduce,
                company_duty: profile.value.dealerCompanyDuty,
                receive_post: profile.value.receive_post,
                receive_addr1: profile.value.receive_addr1,
                receive_addr2: profile.value.receive_addr2,
            },
            file_user_biz:profile.value.file_user_biz,
            file_user_cert:profile.value.file_user_cert,
            file_user_sign:profile.value.file_user_sign,
        };
        if (profile.value.password && profile.value.password !== '') {
          payload.user.password = profile.value.password;
        }
      
        if (profile.value.password_confirmation && profile.value.password_confirmation !== '') {
          payload.user.password_confirmation = profile.value.password_confirmation;
        }
        console.log(JSON.stringify(payload));
        const formData = new FormData();
        formData.append('user', JSON.stringify(payload.user));
        if(profile.value.isDealer){
          formData.append('dealer', JSON.stringify(payload.dealer));
          if (payload.file_user_biz) {
            formData.append('file_user_biz', payload.file_user_biz);
          }
          if (payload.file_user_cert) {
            formData.append('file_user_cert', payload.file_user_cert);
          }
          if (payload.file_user_sign) {
            formData.append('file_user_sign', payload.file_user_sign);
          }
        }
      
        if (profile.value.file_user_photo) {
          formData.append('file_user_photo', profile.value.file_user_photo);
        }
      
        
      
        wica.ntcn(swal)
        .title('수정하시겠습니까?') // 알림 제목
        .icon('Q') //E:error , W:warning , I:info , Q:question
        .callback(async function(result) {
            if (result.isOk) {
                //기존 파일 이미지 삭제
                if(profile.value.photoImgChg){
                  wicac.conn()
                  .url(`/api/media`)
                  .log()
                  .param({
                    "uuid" : [
                      profile.value.photoUUID,
                    ]
                  })
                  .callback(async function(result) {
                  })
                  .delete();
                }
      
                wicac.conn()
                .url(`/api/users/${userId}`)
                .param(formData)
                .multipartUpdate()
                .callback(async function(result) {
                    if(result.isSuccess){
                      wica.ntcn(swal)
                      .icon('I') //E:error , W:warning , I:info , Q:question
                      .callback(function(result2) {
                          if (result2.isOk) {
                            location.reload();
                          }  
                      })
                      .alert('내 정보가 정상적으로 수정되었습니다.');
                    } else{
                      wica.ntcn(swal)
                      .title('변경 실패')
                      .icon('E') //E:error , W:warning , I:info , Q:question
                      .alert('내 정보 변경에 실패하였습니다.');
                    }
                })
                .post();
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
                wicac.conn()
                .url(`/api/users/${id}`) 
                .callback(function(result2) {
                    if(result2.isSuccess){
                        wica.ntcn(swal)
                        .icon('I') //E:error , W:warning , I:info , Q:question
                        .callback(function(result) {
                            if(result.isOk){                                
                                location.reload();                          
                            }
                        })
                        .alert('회원정보가 정상적으로 삭제되었습니다.');
                    }else{
                        wica.ntcn(swal)
                        .title('오류가 발생하였습니다.')
                        .icon('E') //E:error , W:warning , I:info , Q:question
                        .alert('관리자에게 문의해주세요.');
                    }
                })
                .delete();
            }
        })
        .confirm('삭제된 정보는 복구할 수 없습니다.');   
        
    }

    return {
        adminEditForm,
        editForm,
        getUserStatus,
        users,
        user,
        getUser,
        adminGetUsers,
        updateMyInfo,
        updateUser,
        deleteUser,
        validationErrors,
        isLoading,
        adminStoreUser,
        fileUserSignUpload,
        pagination,
        setRegisterUser,
        updateProfile
    }
}
