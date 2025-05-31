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


    console.log('Initializing Alpine...');

    // 이미 시작된 Alpine이 있으면 중단
    if (window.Alpine) {
        console.log('Alpine instance exists but not initialized, resetting...');
    }

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

    // 컴포넌트 등록 여부를 추적
    const registeredComponents = new Set();

    // 컴포넌트 feature 자동 등록 (하위 폴더 포함)
    const components = import.meta.glob('./feature/**/*.js', { eager: true });
    Object.entries(components).forEach(([path, module]) => {
        const name = path.split('/').pop().replace('.js', '');
        if (!registeredComponents.has(name)) {
            Alpine.data(name, module.default);
            registeredComponents.add(name);
        }
    });

    const pages = import.meta.glob('./pages/**/*.js', { eager: true });
    Object.entries(pages).forEach(([path, module]) => {
        const name = path.split('/').pop().replace('.js', '');
        if (!registeredComponents.has(name)) {
            Alpine.data(name, module.default);
            registeredComponents.add(name);
        }
    });

    // boards 폴더 자동 등록
    const boardComponents = import.meta.glob('./boards/**/*.js', { eager: true });
    Object.entries(boardComponents).forEach(([path, module]) => {
        const name = path.split('/').pop().replace('.js', '');

        if (!registeredComponents.has(name)) {
            // console.log(`Registering board component: ${name}`);

            // board.js는 스토어로 등록, 나머지는 컴포넌트로 등록
            if (name === 'board') {
                Alpine.store('board', module.default);
                // console.log('Registered board as store');
            } else {
                Alpine.data(name, module.default);
                // console.log(`Registered ${name} as component`);
            }
            registeredComponents.add(name);
        }
    });

    Alpine.store('modal', modal);

    // window.Alpine = Alpine;

    // Alpine 시작 전에 초기화 플래그 설정
    Alpine._initialized = true;
    Alpine.start();

    console.log('Alpine initialization completed');
