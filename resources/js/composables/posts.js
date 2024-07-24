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
    const { wicac , wica } = cmmn();
    const userId = ref(2); // This is just an example, you can get the real user ID from the authenticated user context.

    const getBoardCategories = async () => {
        try {
            const response = await axios.get('/api/board');
            if (Array.isArray(response.data.data)) {
                const noticeBoard = response.data.data.find(board => board.id === 'notice');
                if (noticeBoard) {
                    categories.value = JSON.parse(noticeBoard.categories);
                }
            } 
        } catch (error) {
            console.error('Error fetching board data:', error);
        }
    };

    const getPosts = async (
        boardId,
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
            const response = await axios.get(`/api/board/${boardId}/articles`, {
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
            posts.value = response.data.data;
            return response.data;
        } catch (error) {
            console.error(error);
            throw error;
        } finally {
            isLoading.value = false;
        }
    };

    const getPost = async (boardId, id) => {
        isLoading.value = true;
        try {
            const response = await axios.get(`/api/board/${boardId}/articles/${id}`);
            post.value = response.data.data;
        } catch (error) {
            console.error(error);
            throw error;
        } finally {
            isLoading.value = false;
        }
    };

    const storePost = async (boardId, postData) => {
        if (isLoading.value) return;
    
        isLoading.value = true;
        validationErrors.value = {};
    
        const serializedPost = new FormData();
        serializedPost.append('article[title]', postData.title);
        serializedPost.append('article[content]', postData.content);
        postData.categories.forEach((category, index) => {
            serializedPost.append(`article[categories][${index}]`, category);
        });
        if (postData.thumbnail) {
            serializedPost.append('article[thumbnail]', postData.thumbnail);
        }
    
        try {
            const response = await axios.post(`/api/board/${boardId}/articles`, serializedPost, {
                headers: {
                    "content-type": "multipart/form-data"
                }
            });
            router.push({ name: 'posts.index', params: { boardId } });
            wica.ntcn(swal)
                .icon('I')
                .alert('공지사항이 성공적으로 저장되었습니다.');
        } catch (error) {
            if (error.response?.data) {
                validationErrors.value = error.response.data.errors;
            }
            throw error;
        } finally {
            isLoading.value = false;
        }
    };
    
    const updatePost = async (boardId, postId, postData) => {
        if (isLoading.value) return;
    
        isLoading.value = true;
        validationErrors.value = {};
    
        const data = {
            article: {
                title: postData.title,
                content: postData.content,
            }
        };
    
        try {
            const response = await axios.put(`/api/board/${boardId}/articles/${postId}`, data, {
                headers: {
                    "content-type": "application/json"
                }
            });
            return response.data;
        } catch (error) {
            if (error.response?.data) {
                validationErrors.value = error.response.data.errors;
            }
            throw error;
        } finally {
            isLoading.value = false;
        }
    };
    
    const deletePost = async (boardId, id) => {
        try {
            wica.ntcn(swal)
                .param({ _id: id }) // 리턴값에 전달 할 데이터
                .title('삭제하시겠습니까?') // 알림 제목
                .icon('W') // E:error , W:warning , I:info , Q:question
                .callback(async function(result) {
                    if (result.isOk) {
                        const response = await axios.delete(`/api/board/${boardId}/articles/${id}`);
                        wica.ntcn(swal)
                            .addClassNm('cmm-review-custom') // 클래스명 변경시 기입, 기본 클래스명 : wica-salert
                            .icon('I') // E:error , W:warning , I:info , Q:question
                            .alert('게시물이 정상적으로 삭제되었습니다.');
                        getPosts(boardId);
                    }
                })
                .confirm('삭제된 정보는 복구할 수 없습니다.');
        } catch (error) {
            console.error('Error deleting post:', error);
            wica.ntcn(swal)
                .title('오류가 발생하였습니다.')
                .icon('E') // E:error , W:warning , I:info , Q:question
                .alert('관리자에게 문의해주세요.');
        }
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
