import Alpine from 'alpinejs';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';
import 'animate.css';

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

// util 파일들 import 및 Alpine 등록
import { api } from './util/axios.js';
import { address } from './util/address.js';
import { fileUpload } from './util/fileUpload.js';

// Alpine 등록
Alpine.store('api', api);
Alpine.store('address', address);
Alpine.data('fileUpload', fileUpload);

// 컴포넌트 feature 자동 등록 (하위 폴더 포함)
const components = import.meta.glob('./feature/**/*.js', { eager: true });
Object.entries(components).forEach(([path, module]) => {
    // 경로에서 파일명만 추출 (확장자 제외)
    const name = path.split('/').pop().replace('.js', '');
    Alpine.data(name, module.default);
});

// TO.성완 / modal.js 와 bootstrap.js 의 충돌이 있습니다. 드롭다운, offcanvas 사용시 충돌.
// import { modal } from './util/modal.js';
// Alpine.store('modal', modal);

// 파일 업로드 컴포넌트 등록
// import { fileUpload } from './util/fileUpload.js';
// Alpine.data('fileUpload', fileUpload);

// document.addEventListener('alpine:init', () => {
//     Alpine.store('modal', modal);
//     Alpine.start();
// });

window.Alpine = Alpine;
Alpine.start();
