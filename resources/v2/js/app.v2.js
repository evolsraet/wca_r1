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
import { api } from './util/axios.js';
Alpine.store('api', api);

// Alpine.js 전역 스토어에 Daum 주소 검색 함수 추가
import { address } from './util/address.js';
Alpine.store('address', address);

// 컴포넌트 feature 자동 등록 (하위 폴더 포함)
const components = import.meta.glob('./feature/**/*.js', { eager: true });
Object.entries(components).forEach(([path, module]) => {
    // 경로에서 파일명만 추출 (확장자 제외)
    const name = path.split('/').pop().replace('.js', '');
    Alpine.data(name, module.default);
});

import { modal } from './util/modal.js';
Alpine.store('modal', modal);

// 파일 업로드 컴포넌트 등록
import { fileUpload } from './util/fileUpload.js';
Alpine.data('fileUpload', fileUpload);

// document.addEventListener('alpine:init', () => {
//     Alpine.store('modal', modal);
//     Alpine.start();
// });

window.Alpine = Alpine;
Alpine.start();
