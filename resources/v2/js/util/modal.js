import { Modal, Collapse, Tooltip } from 'bootstrap';

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

document.addEventListener('DOMContentLoaded', () => {
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltipTriggerList.forEach(el => new Tooltip(el));
});

// 아코디언 버튼 클릭 이벤트
document.querySelectorAll('.accordion-button').forEach(button => {
    const targetSelector = button.dataset.bsTarget;
    const target = document.querySelector(targetSelector);
  
    // Collapse 인스턴스 수동 생성
    let instance = Collapse.getInstance(target);
    if (!instance) {
      instance = new Collapse(target, { toggle: false });
    }
  
    // 버튼 클릭 시 toggle
    button.addEventListener('click', () => {
      const isOpen = target.classList.contains('show');
      isOpen ? instance.hide() : instance.show();
    });
  
    // 상태 UI 동기화: 열렸을 때
    target.addEventListener('shown.bs.collapse', () => {
      button.classList.remove('collapsed');
      button.setAttribute('aria-expanded', 'true');
    });
  
    // 상태 UI 동기화: 닫혔을 때
    target.addEventListener('hidden.bs.collapse', () => {
      button.classList.add('collapsed');
      button.setAttribute('aria-expanded', 'false');
    });
});

export const modal = {
    _modal: null,
    _activeModals: new Map(), // 활성 모달들을 추적

    // 모든 모달과 백드롭 정리
    _cleanupAllModals() {
        // 모든 기존 모달 인스턴스 닫기
        this._activeModals.forEach((modal, id) => {
            try {
                modal.hide();
            } catch (e) {
                console.warn(`Failed to hide modal ${id}:`, e);
            }
        });
        this._activeModals.clear();
        
        // DOM에서 모든 모달 요소 제거
        const existingModals = document.querySelectorAll('.modal');
        existingModals.forEach(modal => modal.remove());
        
        // 모든 백드롭 제거
        const backdrops = document.querySelectorAll('.modal-backdrop, .offcanvas-backdrop');
        backdrops.forEach(backdrop => backdrop.remove());
        
        // body 클래스 정리
        document.body.classList.remove('modal-open', 'offcanvas-open');
        
        // body 스타일 리셋
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';
    },

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
    async show(options = {}) {
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
            onClose = null,
        } = options;

        // 모든 기존 모달 정리
        this._cleanupAllModals();
        
        // 약간의 지연으로 DOM 정리가 완료되도록 함
        await new Promise(resolve => setTimeout(resolve, 100));

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
        const modalInstance = new Modal(modalElement, {
            backdrop: !isOffcanvasOpen,
            keyboard: true,
            focus: true
        });

        // 활성 모달 목록에 추가
        this._activeModals.set(id, modalInstance);

        // 이벤트 리스너 등록
        if (onConfirm) {
            const confirmBtn = modalElement.querySelector('#modalConfirmBtn');
            if (confirmBtn) {
                confirmBtn.addEventListener('click', () => {
                    onConfirm();
                    modalInstance.hide();
                });
            }
        }

        // 모달 닫힘 이벤트 처리
        modalElement.addEventListener('hidden.bs.modal', () => {
            // 활성 모달 목록에서 제거
            this._activeModals.delete(id);
            
            // onClose 콜백 실행
            if (onClose) {
                onClose();
            }
            
            // DOM에서 제거
            modalElement.remove();
            
            // 백드롭 정리
            const remainingBackdrops = document.querySelectorAll('.modal-backdrop');
            remainingBackdrops.forEach(backdrop => backdrop.remove());
            
            // 다른 모달이 없으면 body 상태 정리
            if (this._activeModals.size === 0) {
                document.body.classList.remove('modal-open');
                document.body.style.overflow = '';
                document.body.style.paddingRight = '';
            }
        });

        // 모달 표시
        modalInstance.show();
        this._modal = modalInstance;
        
        return modalInstance;
    },

    // HTML 컨텐츠로 모달 표시
    async showHtml(html, options = {}) {
        return await this.show({
            ...options,
            content: html
        });
    },

    // URL을 통해 HTML을 가져와 모달 표시
    async showHtmlFromUrl(url, options = {}, data = {}) {
        try {
          const response = await fetch(url);
          if (!response.ok) throw new Error(`HTTP error ${response.status}`);
          const html = await response.text();
    
          window.modalData = {
            content: data.content || {},
            result: typeof data.result === 'function' ? data.result : () => {}
          };
    
          const modalInstance = await this.showHtml(html, options);
    
          const shouldInitAlpine = options.initAlpine !== false;
          if (shouldInitAlpine && typeof Alpine !== 'undefined') {
            // Alpine 초기화를 더 안정적으로 처리
            setTimeout(() => {
              const modalBody = document.querySelector('.modal-body');
              if (modalBody) {
                console.log('Alpine initTree 시작');
                try {
                  Alpine.initTree(modalBody);
                  console.log('Alpine initTree 완료');
                } catch (error) {
                  console.error('Alpine initTree 오류:', error);
                  // 재시도
                  setTimeout(() => {
                    try {
                      Alpine.initTree(modalBody);
                      console.log('Alpine initTree 재시도 완료');
                    } catch (retryError) {
                      console.error('Alpine initTree 재시도 실패:', retryError);
                    }
                  }, 200);
                }
              }
            }, 200); // 지연시간을 200ms로 증가
          }
          
          return modalInstance;
        } catch (error) {
          console.error('Error loading modal:', error);
          return await this.showHtml('<div class="alert alert-danger">내용을 불러오는데 실패했습니다.</div>', options);
        }
    },

    // 모달 닫기
    close(modalId = 'dynamicModal') {
        if (modalId) {
            const modalInstance = this._activeModals.get(modalId);
            if (modalInstance) {
                modalInstance.hide();
                return;
            }
        }
        
        // 특정 ID가 없거나 찾지 못한 경우 모든 모달 정리
        this._cleanupAllModals();
    },

    emitResult(result) {
        if (typeof window.modalData?.result === 'function') {
            window.modalData.result(result);
        }
    }
};
