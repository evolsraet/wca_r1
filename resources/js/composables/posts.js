import { ref, reactive, inject } from 'vue';
import { useRouter } from 'vue-router';
import { cmmn } from '@/hooks/cmmn';
import axios from 'axios';

export function initPostSystem() {
    const posts = ref([]);
    const post = ref({
        title: '',
        content: '',
        category: '',
        thumbnail: ''
    });
    const pagination = ref({});
    const router = useRouter();
    const validationErrors = ref({});
    const isLoading = ref(false);
    const swal = inject('$swal');
    const categories = ref([]);
    const { wicac, wica } = cmmn();

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

    const getPosts = async (boardId, page = 1) => {
        return wicac.conn()
            .url(`/api/board/${boardId}/articles`)
            .page(`${page}`)
            .callback(function(result) {
                posts.value = result.data;
                pagination.value = result.rawData.data.meta;
                return result.data;
            })
            .get();
    };

    const getPost = async (boardId, id) => {
        isLoading.value = true;
        try {
            return wicac.conn()
                .url(`/api/board/${boardId}/articles/${id}`) 
                .with(['comments',
                        'media'
                ]) 
                .callback(function(result) {
                    post.value = result.data;
                    return result.data;
                })
                .get(); 
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
        if (boardId === 'notice') {
            serializedPost.append('article[category]', postData.category);
        }
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

        if (boardId === 'notice') {
            data.article.category = postData.category;
        }

        const serializedPost = new FormData();
        serializedPost.append('article', JSON.stringify(data.article));

        if(postData.board_attach){
            serializedPost.append('board_attach', postData.board_attach);
        }

        let alimMsg = ''
        if(boardId === 'notice'){
            alimMsg = '해당 공지사항을 수정 하시겠습니까?';
        }else{
            alimMsg = '해당 클레임을 수정 하시겠습니까?';
        }

        wica.ntcn(swal)
        .icon('Q') //E:error , W:warning , I:info , Q:question , S:success
        .useClose() // 닫기 버튼 활성화본
        .callback(function(result3) {
            if(result3.isOk){
                wicac.conn()
                .url(`/api/board/${boardId}/articles/${postId}`) 
                .multipartUpdate() 
                .param(serializedPost)
                .callback(function(result) {
                    if(result.isSuccess){
                        if(postData.fileDeleteChk && postData.fileUUID){
                            deleteBoardAttachFile(postData.fileUUID);
                        }

                        let successMsg='';
                        if(boardId === 'notice'){
                            successMsg = '공지사항이 성공적으로 수정되었습니다.';
                        }else{
                            successMsg = '클레임이 성공적으로 수정되었습니다.';
                        }
                        wica.ntcn(swal)
                        .icon('I')
                        .callback(function(result2) {
                            if(result2.isOk){
                                isLoading.value = false;
                                router.push({ name: 'posts.index', params: { boardId } }); 
                                //return result.data;                           
                            }
                        })
                        .alert(successMsg);
                        
                    }else{
                        isLoading.value = false;
                        validationErrors.value = result.msg;
                    }
                })
                .post();
            }
        })
        .alert(alimMsg);
    };

    const deletePost = async (boardId, id) => {
        try {
            wica.ntcn(swal)
                .param({ _id: id })
                .title('삭제하시겠습니까?')
                .icon('W')
                .callback(async function(result) {
                    if (result.isOk) {
                        const response = await axios.delete(`/api/board/${boardId}/articles/${id}`);
                        wica.ntcn(swal)
                            .addClassNm('cmm-review-custom')
                            .icon('I')
                            .alert('게시물이 정상적으로 삭제되었습니다.');
                        getPosts(boardId);
                    }
                })
                .confirm('삭제된 정보는 복구할 수 없습니다.');
        } catch (error) {
            console.error('Error deleting post:', error);
            wica.ntcn(swal)
                .title('오류가 발생하였습니다.')
                .icon('E')
                .alert('관리자에게 문의해주세요.');
        }
    };

    const addCommentAPI = async (commentData) => {
        try {
          const response = await axios.post('/api/comments', {
            comment: {
              commentable_type: 'article',
              commentable_id: commentData.commentable_id,
              content: commentData.content
            }
          });
          return response.data;
        } catch (error) {
          console.error('Error adding comment:', error);
          throw error;
        }
      };
      
      const deleteComment = async (commentId) => {
        try {
          console.log('Attempting to delete comment with ID:', commentId);
          wica.ntcn(swal)
            .param({ _id: commentId })
            .title('삭제하시겠습니까?')
            .addClassNm('cmm-comment')
            .icon('I') // W:warning 아이콘 사용
            .callback(async function(result) {
              if (result.isOk) {
                try {
                  const response = await axios.delete(`/api/comments/${commentId}`, {
                    params: {
                      where: 'comments.commentable_type:like:article'
                    }
                  });
                  console.log('Delete response:', response);
                  if (response.status === 200) {
                    // 댓글이 정상적으로 삭제되었을 때
                    wica.ntcn(swal)
                      .addClassNm('cmm-remove')
                      .icon('I') // I:info 아이콘 사용
                      .alert('댓글이 정상적으로 삭제되었습니다.')
                      setTimeout(() => {
                        location.reload(); 
                      }, 1000);
                  } else {
                    // 삭제 실패 시
                    wica.ntcn(swal)
                      .title('댓글 삭제 실패')
                      .icon('E') // E:error 아이콘 사용
                      .alert('댓글 삭제에 실패했습니다.');
                  }
                } catch (error) {
                  console.error('Error deleting comment:', error);
                  wica.ntcn(swal)
                    .title('오류가 발생하였습니다.')
                    .icon('E') // E:error 아이콘 사용
                    .alert('댓글 삭제 중 오류가 발생했습니다.');
                }
              }
            })
            .confirm('삭제된 정보는 복구할 수 없습니다.');
        } catch (error) {
          console.error('Error deleting comment:', error);
          wica.ntcn(swal)
            .title('오류가 발생하였습니다.')
            .icon('E') // E:error 아이콘 사용
            .alert('관리자에게 문의해주세요.');
        }
      };
      
      const editComment = async (commentId, newContent) => {
        try {
            console.log('Attempting to edit comment with ID:', commentId);
            wica.ntcn(swal)
                .param({ _id: commentId })
                .title('댓글을 수정하시겠습니까?')
                .addClassNm('cmm-comment')
                .icon('I') // I:info 아이콘 사용
                .callback(async function(result) {
                    if (result.isOk) {
                        try {
                            const response = await axios.put(`/api/comments/${commentId}`, {
                                comment: {
                                    content: newContent
                                }
                            });
                            console.log('Edit response:', response);
                            if (response.status === 200) {
                                // 댓글이 정상적으로 수정되었을 때
                                wica.ntcn(swal)
                                    .addClassNm('cmm-update')
                                    .icon('I') // I:info 아이콘 사용
                                    .alert('댓글이 정상적으로 수정되었습니다.');
                                setTimeout(() => {
                                    location.reload(); 
                                }, 1000);
                            } else {
                                // 수정 실패 시
                                wica.ntcn(swal)
                                    .title('댓글 수정 실패')
                                    .icon('E') // E:error 아이콘 사용
                                    .alert('댓글 수정에 실패했습니다.');
                            }
                        } catch (error) {
                            console.error('Error editing comment:', error);
                            wica.ntcn(swal)
                                .title('오류가 발생하였습니다.')
                                .icon('E') // E:error 아이콘 사용
                                .alert('댓글 수정 중 오류가 발생했습니다.');
                        }
                    }
                })
                .confirm('');
        } catch (error) {
            console.error('Error editing comment:', error);
            wica.ntcn(swal)
                .title('오류가 발생하였습니다.')
                .icon('E') // E:error 아이콘 사용
                .alert('관리자에게 문의해주세요.');
        }
    };

    const deleteBoardAttachFile = async (UUID) =>{
        wicac.conn()
        .url(`/api/media/${UUID}`)
        .log()
        .callback(async function(result) {
        })
        .delete();
    }
    
      
    

    return {
        posts,
        post,
        getPosts,
        getPost,
        storePost,
        updatePost,
        deletePost,
        addCommentAPI,
        deleteComment,
        editComment,
        validationErrors,
        isLoading,
        categories,
        getBoardCategories,
        pagination
    };
}
