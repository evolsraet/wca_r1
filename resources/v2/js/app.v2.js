import Alpine from 'alpinejs';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';

// sweetalert 전역 설정
    import Swal from 'sweetalert2';
    Alpine.store('swal', Swal);

// toastr 설정
    import toastr from 'toastr';
    import 'toastr/build/toastr.min.css';
    toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: 'toast-top-right',
        timeOut: 3000
    };
    Alpine.store('toastr', toastr);

// 파일 업로드 전역 함수 등록
    import { api, fileUploader, appendFilesFormData } from './util/axios.js';
    Alpine.store('api', api);
    Alpine.store('fileUploader', fileUploader);
    Alpine.store('appendFilesFormData', appendFilesFormData);

// Alpine.js 전역 스토어에 Daum 주소 검색 함수 추가
Alpine.store('address', {
    async openPostcode(elementId) {
        try {
            // 주소 검색 버튼 클릭 시에만 스크립트 로드
            if (!window.daum || !window.daum.Postcode) {
                await new Promise((resolve, reject) => {
                    const script = document.createElement('script');
                    script.src = '//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js';
                    script.async = true;
                    script.onload = resolve;
                    script.onerror = () => reject(new Error('Daum 주소 검색 스크립트 로드 실패'));
                    document.head.appendChild(script);
                });
            }

            return new Promise((resolve) => {
                const element = document.getElementById(elementId);
                // 기존 검색 창이 있다면 제거
                element.innerHTML = '';

                new window.daum.Postcode({
                    oncomplete: function(data) {
                        // 주소 선택 후 검색 창 제거
                        element.innerHTML = '';
                        element.style.display = 'none';
                        resolve({
                            zonecode: data.zonecode,
                            address: data.address
                        });
                    },
                    // 주소 검색 창이 버튼 아래에 나타나도록 설정
                    onresize: function(size) {
                        if (element) {
                            element.style.width = size.width + 'px';
                            element.style.height = size.height + 'px';
                        }
                    },
                    width: '100%',
                    height: '400px'
                }).embed(element);
            });
        } catch (error) {
            console.error('주소 검색 오류:', error);
            alert('주소 검색 서비스를 불러오는 중입니다. 잠시 후 다시 시도해주세요.');
        }
    }
});

// 컴포넌트 feature 자동 등록 (하위 폴더 포함)
const components = import.meta.glob('./feature/**/*.js', { eager: true });
Object.entries(components).forEach(([path, module]) => {
    // 경로에서 파일명만 추출 (확장자 제외)
    const name = path.split('/').pop().replace('.js', '');
    Alpine.data(name, module.default);
});

import { modal } from './util/modal.js';
Alpine.store('modal', modal);

// document.addEventListener('alpine:init', () => {
//     Alpine.store('modal', modal);
//     Alpine.start();
// });

// 파일 업로드 컴포넌트 등록
Alpine.data('fileUpload', (fieldName) => ({
    previewUrl: '',
    handleFileSelect(event) {
        const file = event.target.files[0];
        if (!file) return;

        try {
            // 이미지인 경우 미리보기 URL 생성
            if (file.type.startsWith('image/')) {
                this.previewUrl = URL.createObjectURL(file);
            }

            // 파일 데이터를 이벤트로 전달
            this.$dispatch('file-selected', {
                fieldName,
                file,
                fileName: file.name,
                previewUrl: this.previewUrl
            });
        } catch (error) {
            Alpine.store('toastr').error('파일 처리 중 오류가 발생했습니다.');
        }
    },
    removeFile() {
        try {
            // 파일 제거 이벤트 발생
            this.$dispatch('file-removed', {
                fieldName
            });

            this.previewUrl = '';
        } catch (error) {
            Alpine.store('toastr').error('파일 제거 중 오류가 발생했습니다.');
        }
    }
}));

window.Alpine = Alpine;
Alpine.start();
