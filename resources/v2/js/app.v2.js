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
import whereBuilder from './util/whereBuilder.js';
import { auctionStatus, auctionEvent } from './util/auctions.js';
import common from './util/common.js';
import { like } from './util/likes.js';
import './util/share.js'; // 공유 기능 전역 함수

    console.log('Initializing Alpine...');

    // 이미 시작된 Alpine이 있으면 중단
    if (window.Alpine) {
        console.log('Alpine instance exists but not initialized, resetting...');
    }

    // sweetAlert2 설정
    Alpine.store('swal', Swal);

    // toastr 설정
    toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: 'toast-top-right',
        timeOut: 3000
    };
    Alpine.store('toastr', toastr);

    // 유틸리티 stores 등록
    Alpine.store('api', api);
    Alpine.store('address', address);
    Alpine.store('whereBuilder', whereBuilder);
    Alpine.store('auctionStatus', auctionStatus);
    Alpine.store('auctionEvent', auctionEvent);
    Alpine.store('common', common);
    Alpine.store('like', like);

    // 기타 등록
    Alpine.data('fileUpload', fileUpload);

    // 컴포넌트 등록 여부를 추적
    const registeredComponents = new Set();

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
            if (name === 'board') {
                Alpine.store('board', module.default);
            } else {
                Alpine.data(name, module.default);
            }
            registeredComponents.add(name);
        }
    });

    const adminComponents = import.meta.glob('./admin/**/*.js', { eager: true });
    Object.entries(adminComponents).forEach(([path, module]) => {
        const name = path.split('/').pop().replace('.js', '');
        if (!registeredComponents.has(name)) {
            Alpine.data(name, module.default);
            registeredComponents.add(name);
        }
    });

    // 컴포넌트 feature 자동 등록 (하위 폴더 포함)
    const components = import.meta.glob('./feature/**/*.js', { eager: true });
    Object.entries(components).forEach(([path, module]) => {
        const name = path.split('/').pop().replace('.js', '');
        if (!registeredComponents.has(name)) {
            Alpine.data(name, module.default);
            registeredComponents.add(name);
        }
    });

    


    Alpine.store('likeState', {
        isLiked: false,
        likeId: null,
    });

    // 좋아요 추가 삭제
    // likeListeners();

    Alpine.store('modal', modal);

    // window.Alpine = Alpine;
    window.Alpine = Alpine;

    // Alpine 시작 전에 초기화 플래그 설정
    Alpine._initialized = true;

    // Alpine 시작
    Alpine.start();

    console.log('Alpine initialization completed');
