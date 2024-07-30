import { ref, inject } from 'vue';
import { useRouter } from 'vue-router';
import { cmmn } from '@/hooks/cmmn';

export default function useCategories() {
    const categories = ref([]);
    const categoryList = ref([]);
    const category = ref({
        name: ''
    });

    const router = useRouter();
    const validationErrors = ref({});
    const isLoading = ref(false);
    const swal = inject('$swal');
    const { wicac, wica } = cmmn();

    const getCategories = async (
        page = 1,
        search_id = '',
        search_title = '',
        search_global = '',
        order_column = 'created_at',
        order_direction = 'desc',
        withComments = false
    ) => {
        const apiList = [];

        if (search_id) apiList.push(`search_id:${search_id}`);
        if (search_title) apiList.push(`search_title:${search_title}`);
        if (search_global) apiList.push(`search_global:${search_global}`);

        let request = wicac.conn()
            .url(`/api/categories`)
            .where(apiList)
            .order([
                [order_column, order_direction]
            ])
            .page(`${page}`);

        if (withComments) {
            request = request.with(['comments']);
        }

        return request.callback(function(result) {
            categories.value = result.data;
            return result.data;
        }).get();
    };

    const getCategory = async (id, withComments = false) => {
        let request = wicac.conn().url(`/api/categories/${id}`);
        if (withComments) {
            request = request.with(['comments']);
        }

        return request.callback(function(result) {
            category.value = result.data.data;
            return result.data.data;
        }).get();
    };

    const storeCategory = async (categoryData) => {
        if (isLoading.value) return;

        isLoading.value = true;
        validationErrors.value = {};

        return wicac.conn()
            .url('/api/categories')
            .param(categoryData)
            .callback(function(result) {
                if (result.isError) {
                    validationErrors.value = result.rawData.response.data.errors;
                    throw new Error(result.rawData.response.data.errors);
                } else {
                    router.push({ name: 'categories.index' });
                    swal({
                        icon: 'success',
                        title: 'Category saved successfully'
                    });
                    return result.isSuccess;
                }
            })
            .post()
            .finally(() => isLoading.value = false);
    };

    const updateCategory = async (categoryData) => {
        if (isLoading.value) return;

        isLoading.value = true;
        validationErrors.value = {};

        return wicac.conn()
            .url(`/api/categories/${categoryData.id}`)
            .param(categoryData)
            .callback(function(result) {
                if (result.isError) {
                    validationErrors.value = result.rawData.response.data.errors;
                    throw new Error(result.rawData.response.data.errors);
                } else {
                    router.push({ name: 'categories.index' });
                    swal({
                        icon: 'success',
                        title: 'Category updated successfully'
                    });
                    return result.isSuccess;
                }
            })
            .put()
            .finally(() => isLoading.value = false);
    };

    const deleteCategory = async (id) => {
        swal({
            title: '해당 글을 지우시겠습니까?',
            text: '지우신 글은 복구 불가능 합니다.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '예',
            confirmButtonColor: '#ef4444',
            timer: 20000,
            timerProgressBar: true,
            reverseButtons: true
        }).then(result => {
            if (result.isConfirmed) {
                return wicac.conn()
                    .url(`/api/categories/${id}`)
                    .callback(function(result) {
                        if (result.isSuccess) {
                            getCategories();
                            router.push({ name: 'categories.index' });
                            swal({
                                icon: 'success',
                                title: '성공적으로 지웠습니다'
                            });
                        } else {
                            swal({
                                icon: 'error',
                                title: '삭제 과정에서 오류가있습니다'
                            });
                        }
                    })
                    .delete();
            }
        });
    };

    const getCategoryList = async () => {
        return wicac.conn()
            .url('/api/category-list')
            .callback(function(result) {
                categoryList.value = result.data.data;
                return result.data.data;
            })
            .get();
    };

    return {
        categoryList,
        categories,
        category,
        getCategories,
        getCategoryList,
        getCategory,
        storeCategory,
        updateCategory,
        deleteCategory,
        validationErrors,
        isLoading
    };
}
