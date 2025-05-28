<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>파일 업로드 테스트</title>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h2>파일 업로드 테스트</h2>

        <form x-data="fileUpload()" @submit.prevent="uploadFile" class="mt-4">
            <div class="mb-3">
                <label class="form-label">파일 선택</label>
                <input type="file" class="form-control" @change="handleFile" />
            </div>

            <div class="mb-3">
                <button type="submit" class="btn btn-primary">업로드</button>
            </div>

            <div x-show="file" class="mt-3">
                <p>선택된 파일: <span x-text="file ? file.name : '없음'"></span></p>
            </div>
        </form>
    </div>

    <script>
        function fileUpload() {
            return {
                file: null,

                handleFile(event) {
                    const selectedFile = event.target.files[0];
                    if (selectedFile) {
                        this.file = selectedFile;
                        console.log('File selected:', this.file);
                    } else {
                        this.file = null;
                        console.log('No file selected');
                    }
                },

                async uploadFile() {
                    if (!this.file) {
                        alert('파일을 선택하세요.');
                        return;
                    }

                    const formData = new FormData();
                    formData.append('file', this.file);

                    // FormData 내용 확인
                    console.log('FormData contents:');
                    for (let pair of formData.entries()) {
                        console.log(pair[0], pair[1]);
                    }

                    try {
                        const response = await axios.post('/api/test/upload', formData, {
                            headers: {
                                'Content-Type': 'multipart/form-data',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });

                        console.log('Upload response:', response.data);
                        alert('업로드 성공: ' + response.data.message);
                    } catch (error) {
                        console.error('Upload error:', error);
                        alert('업로드 실패: ' + (error.response?.data?.message || '알 수 없는 오류'));
                    }
                }
            };
        }
    </script>
</body>
</html>
