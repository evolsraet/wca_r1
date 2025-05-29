export const fileUpload = (fieldName) => ({
    previewUrl: '',
    fileList: [],
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
    }
});
