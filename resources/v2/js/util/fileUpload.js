import { api } from './axios';

// 파일 처리 유틸리티 함수
export const appendFilesToFormData = (formData, fileFields, element) => {
    // const fileFields = Array.from(element.querySelectorAll('input[type="file"]')).map(input => input.name);
    // console.log('fileFields', fileFields);

    // console.log('appendFilesToFormData', formData, fileFields, element);
    fileFields.forEach(fieldName => {
        let fileInput = element.querySelector(`input[type="file"][name="${fieldName}"]`);
        if (!fileInput) {
            fileInput = element.querySelector(`input[type="file"][name="${fieldName}[]"]`);
            fieldName = fieldName + '[]';
            if (!fileInput) {
                return;
            }
        }

        // console.log('fileInput', fileInput.name, fieldName);

        if (fieldName.endsWith('[]')) {
            // 멀티플 파일 처리
            Array.from(fileInput.files).forEach(file => {
                formData.append(fieldName, file);
            });
        } else {
            // 단일 파일 처리
            if (fileInput.files.length > 0) {
                formData.append(fieldName, fileInput.files[0]);
            }
        }
    });
};

// 파일 업로드 이벤트 리스너 설정 함수
export const setupFileUploadListeners = (form, element) => {
    const listeners = {
        handleFileSelected(event) {
            const { fieldName, file } = event.detail;
            if (fieldName.endsWith('[]')) {
                // 멀티플 파일 처리
                const baseFieldName = fieldName.slice(0, -2);
                if (!form[baseFieldName]) {
                    form[baseFieldName] = [];
                }
                form[baseFieldName].push(file);
            } else {
                // 단일 파일 처리
                form[fieldName] = file;
            }
        },

        handleFileRemoved(event) {
            const { fieldName, index } = event.detail;
            if (fieldName.endsWith('[]')) {
                // 멀티플 파일 제거
                const baseFieldName = fieldName.slice(0, -2);
                if (index !== undefined && form[baseFieldName]) {
                    form[baseFieldName].splice(index, 1);
                }
            } else {
                // 단일 파일 제거
                form[fieldName] = null;
            }
        }
    };

    // 이벤트 리스너 자동 등록
    element.addEventListener('file-selected', listeners.handleFileSelected);
    element.addEventListener('file-removed', listeners.handleFileRemoved);

    return listeners;
};

export const fileUpload = (fieldName) => ({
    previewUrl: '',
    fileList: [],
    existingFiles: [],

    init() {
        // 기존 파일 삭제 이벤트 리스너
        this.$el.addEventListener('click', (e) => {
            if (e.target.matches('[data-delete-file]') || e.target.closest('[data-delete-file]')) {
                const button = e.target.matches('[data-delete-file]') ? e.target : e.target.closest('[data-delete-file]');
                const fileUuid = button.dataset.fileUuid;
                this.deleteExistingFile(fileUuid);
            }
        });
    },

    handleFileSelect(event) {
        const files = Array.from(event.target.files);
        if (files.length === 0) return;

        try {
            const isMultiple = fieldName.endsWith('[]');
            if (isMultiple) {
                // 멀티플 파일 처리
                files.forEach(file => {
                    this.fileList.push({
                        file: file,
                        name: file.name,
                        previewUrl: file.type.startsWith('image/') ? URL.createObjectURL(file) : ''
                    });

                    // 파일 데이터를 이벤트로 전달
                    this.$dispatch('file-selected', {
                        fieldName,
                        file,
                        fileName: file.name,
                        previewUrl: file.type.startsWith('image/') ? URL.createObjectURL(file) : ''
                    });
                });
            } else {
                // 단일 파일 처리
                const file = files[0];
                this.fileList = [{
                    file: file,
                    name: file.name,
                    previewUrl: file.type.startsWith('image/') ? URL.createObjectURL(file) : ''
                }];

                // 파일 데이터를 이벤트로 전달
                this.$dispatch('file-selected', {
                    fieldName,
                    file,
                    fileName: file.name,
                    previewUrl: file.type.startsWith('image/') ? URL.createObjectURL(file) : ''
                });
            }
        } catch (error) {
            console.error('FileUpload - Error:', error);
            Alpine.store('toastr').error('파일 처리 중 오류가 발생했습니다.');
        }
    },

    removeFile(index) {
        try {
            if (index === undefined) {
                // 단일 파일 제거
                const removedFile = this.fileList[0];
                this.fileList = [];

                // 파일 제거 이벤트 발생
                if (removedFile) {
                    this.$dispatch('file-removed', {
                        fieldName,
                        fileName: removedFile.name
                    });
                }
            } else {
                // 특정 파일 제거
                const removedFile = this.fileList[index];
                this.fileList.splice(index, 1);

                // 파일 제거 이벤트 발생
                if (removedFile) {
                    this.$dispatch('file-removed', {
                        fieldName,
                        index,
                        fileName: removedFile.name
                    });
                }
            }

            // 파일 입력 필드 초기화 (안전하게 처리)
            const fileInput = this.$el.querySelector(`input[type="file"][name="${fieldName}"]`);
            if (fileInput) {
                fileInput.value = '';
            }
        } catch (error) {
            console.error('FileUpload - Error in removeFile:', error);
            Alpine.store('toastr').error('파일 제거 중 오류가 발생했습니다.');
        }
    },

    async deleteExistingFile(fileUuid) {
        if (confirm('정말로 이 파일을 삭제하시겠습니까?')) {
            try {
                await api.delete(`/api/media/${fileUuid}`);

                // 파일 목록에서 제거
                const fileElement = this.$el.querySelector(`[data-file-uuid="${fileUuid}"]`);
                if (fileElement) {
                    fileElement.remove();
                }

                Alpine.store('toastr').success('파일이 삭제되었습니다.');
            } catch (error) {
                Alpine.store('toastr').error('파일 삭제 중 오류가 발생했습니다.');
            }
        }
    }
});
