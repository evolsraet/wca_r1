import { ref, reactive, inject } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';
import { cmmn } from '@/hooks/cmmn';

export function initPostSystem() {
    const posts = ref([]);
    const post = ref({
        title: '',
        content: '',
        categories: [],
        thumbnail: ''
    });
    const router = useRouter();
    const validationErrors = ref({});
    const isLoading = ref(false);
    const swal = inject('$swal');
    const categories = ref([]);
    const boardId = ref('notice'); // 게시판 ID를 'notice'로 설정
    const { wicac , wica } = cmmn();

    const getBoardCategories = async () => {
        try {
            const response = await axios.get('/api/board');
            console.log('Board Data:', response.data); 
            if (Array.isArray(response.data.data)) {
                const noticeBoard = response.data.data.find(board => board.id === 'notice');
                if (noticeBoard) {
                    categories.value = JSON.parse(noticeBoard.categories);
                    console.log('Notice Categories:', categories.value); // 카테고리 데이터 로그 출력
                }
            } 
        } catch (error) {
            console.error('Error fetching board data:', error);
        }
    };

    const getPosts = async (
        page = 1,
        search_category = '',
        search_id = '',
        search_title = '',
        search_content = '',
        search_global = '',
        order_column = 'created_at',
        order_direction = 'desc'
    ) => {
        isLoading.value = true;
        try {
            const response = await axios.get(`/api/board/${boardId.value}/articles`, {
                params: {
                    page,
                    search_category,
                    search_id,
                    search_title,
                    search_content,
                    search_global,
                    order_column,
                    order_direction
                }
            });
            console.log('Posts Data:', response.data); // 응답 데이터 로그 출력
            posts.value = response.data.data;
            return response.data;
        } catch (error) {
            console.error(error);
        } finally {
            isLoading.value = false;
        }
    };

    const getPost = async (id) => {
        isLoading.value = true;
        try {
            const response = await axios.get(`/api/board/${boardId.value}/articles/${id}`);
            post.value = response.data.data;
            console.log('Post Data:', response.data); // 응답 데이터 로그 출력
        } catch (error) {
            console.error(error);
        } finally {
            isLoading.value = false;
        }
    };

    const storePost = async (postData) => {
        if (isLoading.value) return;
    
        isLoading.value = true;
        validationErrors.value = {};
    
        const serializedPost = new FormData();
        serializedPost.append('title', postData.title);
        serializedPost.append('content', postData.content);
        if (postData.thumbnail) {
            serializedPost.append('thumbnail', postData.thumbnail);
        }
    
        console.log('Serialized Post Data:', Object.fromEntries(serializedPost.entries())); // 전송 데이터 로그 출력
    
        try {
            const response = await axios.post(`/api/board/${boardId.value}/articles`, serializedPost, {
                headers: {
                    "content-type": "multipart/form-data"
                }
            });
            console.log('Store Post Response:', response.data); // 응답 데이터 로그 출력
            router.push({ name: 'posts.index' });
            wica.ntcn(swal)
                .icon('I')
                .alert('공지사항이 성공적으로 저장되었습니다.');
        } catch (error) {
            console.log('Error during submitNotice:', error);
            if (error.response?.data) {
                validationErrors.value = error.response.data.errors;
                console.log('Validation Errors:', validationErrors.value); // 유효성 검사 오류 로그 출력
            }
        } finally {
            isLoading.value = false;
        }
    };
    
    const updatePost = async (postData) => {
        if (isLoading.value) return;

        isLoading.value = true;
        validationErrors.value = {};

        try {
            const response = await axios.put(`/api/board/${boardId.value}/articles/${postData.id}`, postData);
            console.log('Update Post Response:', response.data); // 응답 데이터 로그 출력
            router.push({ name: 'posts.index' });
            swal({
                icon: 'success',
                title: 'Post updated successfully'
            });
        } catch (error) {
            if (error.response?.data) {
                validationErrors.value = error.response.data.errors;
                console.log('Validation Errors:', validationErrors.value); // 유효성 검사 오류 로그 출력
            }
        } finally {
            isLoading.value = false;
        }
    };

    const deletePost = async (id) => {
        wica.ntcn(swal)
            .param({ _id: id }) // 리턴값에 전달 할 데이터
            .title('삭제하시겠습니까?') // 알림 제목
            .icon('W') // E:error , W:warning , I:info , Q:question
            .callback(function(result) {
                if (result.isOk) {
                    wicac.conn()
                        .url(`/api/board/${boardId.value}/articles/${id}`)
                        .callback(function(result2) {
                            if (result2.isSuccess) {
                                wica.ntcn(swal)
                                    .addClassNm('cmm-review-custom') // 클래스명 변경시 기입, 기본 클래스명 : wica-salert
                                    .icon('I') // E:error , W:warning , I:info , Q:question
                                    .callback(function(result3) {
                                        if (result3.isOk) {                                
                                            getPosts();                                
                                        }
                                    })
                                    .alert('게시물이 정상적으로 삭제되었습니다.');
                            } else {
                                wica.ntcn(swal)
                                    .title('오류가 발생하였습니다.')
                                    .icon('E') // E:error , W:warning , I:info , Q:question
                                    .alert('관리자에게 문의해주세요.');
                            }
                        })
                        .delete();
                }
            })
            .confirm('삭제된 정보는 복구할 수 없습니다.');
    };

    return {
        posts,
        post,
        getPosts,
        getPost,
        storePost,
        updatePost,
        deletePost,
        validationErrors,
        isLoading,
        categories,
        getBoardCategories
    };
}
