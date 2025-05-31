import Alpine from 'alpinejs';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';
import 'animate.css';

// sweetalert 전역 설정
import Swal from 'sweetalert2';
// toastr 설정
import toastr from 'toastr';
import 'toastr/build/toastr.min.css';
// util 파일들 import
import { api } from './util/axios.js';
import { address } from './util/address.js';
import { fileUpload } from './util/fileUpload.js';
import { modal } from './util/modal.js';

// Alpine 중복 초기화 방지
if (window.Alpine) {
    console.log('Alpine already initialized, skipping...');
} else {
    console.log('Initializing Alpine...');

    Alpine.store('swal', Swal);

    toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: 'toast-top-right',
        timeOut: 3000
    };
    Alpine.store('toastr', toastr);

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

    const pages = import.meta.glob('./pages/**/*.js', { eager: true });
    Object.entries(pages).forEach(([path, module]) => {
        // 경로에서 파일명만 추출 (확장자 제외)
        const name = path.split('/').pop().replace('.js', '');
        Alpine.data(name, module.default);
    });

    // boards 폴더 자동 등록
    const boardComponents = import.meta.glob('./boards/**/*.js', { eager: true });
    Object.entries(boardComponents).forEach(([path, module]) => {
        // 경로에서 파일명만 추출 (확장자 제외)
        const name = path.split('/').pop().replace('.js', '');
        console.log(`Registering board component: ${name}`);

        // board.js는 스토어로 등록, 나머지는 컴포넌트로 등록
        if (name === 'board') {
            Alpine.store('board', module.default);
            console.log('Registered board as store');
        } else {
            Alpine.data(name, module.default);
            console.log(`Registered ${name} as component`);
        }
    });

    Alpine.store('modal', modal);

    window.Alpine = Alpine;
    Alpine.start();

    console.log('Alpine initialization completed');
}
