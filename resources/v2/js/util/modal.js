import { Modal } from 'bootstrap';

// offcanvas backdrop 관리를 위한 전역 변수
let isOffcanvasOpen = false;

// offcanvas 이벤트 리스너 등록
document.addEventListener('show.bs.offcanvas', () => {
    isOffcanvasOpen = true;
    // 기존 backdrop 제거
    const backdrops = document.querySelectorAll('.offcanvas-backdrop');
    backdrops.forEach(backdrop => backdrop.remove());
});

document.addEventListener('hidden.bs.offcanvas', () => {
    isOffcanvasOpen = false;
    // backdrop 제거
    const backdrops = document.querySelectorAll('.offcanvas-backdrop');
    backdrops.forEach(backdrop => backdrop.remove());
});

export const modal = {
    _modal: null,

    // HTML 템플릿 생성 함수
    _createModalTemplate(id, options = {}) {
        const {
            title = '제목',
            content = '내용',
            size = '', // 'modal-sm', 'modal-lg', 'modal-xl' 등
            showClose = true,
            showFooter = true,
            footerButtons = [
                { text: '닫기', class: 'btn-secondary', dismiss: true },
                { text: '확인', class: 'btn-primary', id: 'modalConfirmBtn' }
            ]
        } = options;

        return `
            <div class="modal fade" id="${id}" tabindex="-1" aria-labelledby="${id}Label" aria-hidden="true">
                <div class="modal-dialog ${size}">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="${id}Label">${title}</h5>
                            ${showClose ? '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>' : ''}
                        </div>
                        <div class="modal-body">
                            ${content}
                        </div>
                        ${showFooter ? `
                            <div class="modal-footer">
                                ${footerButtons.map(btn => `
                                    <button type="button"
                                        class="btn ${btn.class}"
                                        ${btn.dismiss ? 'data-bs-dismiss="modal"' : ''}
                                        ${btn.id ? `id="${btn.id}"` : ''}>
                                        ${btn.text}
                                    </button>
                                `).join('')}
                            </div>
                        ` : ''}
                    </div>
                </div>
            </div>
        `;
    },

    // 동적 모달 생성 및 표시
    show(options = {}) {
        const {
            id = 'dynamicModal',
            title = '제목',
            content = '내용',
            size = '',
            showClose = true,
            showFooter = true,
            footerButtons = [
                { text: '닫기', class: 'btn-secondary', dismiss: true },
                { text: '확인', class: 'btn-primary', id: 'modalConfirmBtn' }
            ],
            onConfirm = null,
            onClose = null
        } = options;

        // 기존 모달 제거
        const existingModal = document.getElementById(id);
        if (existingModal) {
            existingModal.remove();
        }

        // 새 모달 생성
        const modalHtml = this._createModalTemplate(id, {
            title,
            content,
            size,
            showClose,
            showFooter,
            footerButtons
        });

        // 모달 추가
        document.body.insertAdjacentHTML('beforeend', modalHtml);

        // 모달 인스턴스 생성
        const modalElement = document.getElementById(id);
        const modal = new Modal(modalElement, {
            backdrop: !isOffcanvasOpen
        });

        // 이벤트 리스너 등록
        if (onConfirm) {
            const confirmBtn = modalElement.querySelector('#modalConfirmBtn');
            if (confirmBtn) {
                confirmBtn.addEventListener('click', () => {
                    onConfirm();
                    modal.hide();
                });
            }
        }

        if (onClose) {
            modalElement.addEventListener('hidden.bs.modal', () => {
                onClose();
                modalElement.remove();
            });
        } else {
            modalElement.addEventListener('hidden.bs.modal', () => {
                modalElement.remove();
            });
        }

        // 모달 표시
        modal.show();
        this._modal = modal;
    },

    // HTML 컨텐츠로 모달 표시
    showHtml(html, options = {}) {
        this.show({
            ...options,
            content: html
        });
    },

    // URL을 통해 HTML을 가져와 모달 표시
    async showHtmlFromUrl(url, options = {}) {
        try {
            const response = await fetch(url);
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            const html = await response.text();
            this.showHtml(html, options);
        } catch (error) {
            console.error('Error loading modal content:', error);
            this.showHtml('<div class="alert alert-danger">내용을 불러오는데 실패했습니다.</div>', options);
        }
    },

    // 모달 닫기
    close(modalId = 'dynamicModal') {
        const modalElement = document.getElementById(modalId);
        if (modalElement) {
            const modal = Modal.getInstance(modalElement);
            if (modal) {
                modal.hide();
            }
        }
    }
};
