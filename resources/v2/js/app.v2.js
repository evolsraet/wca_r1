import Alpine from 'alpinejs';

// 자동 모듈 로딩을 위한 함수
const loadModule = async (modulePath) => {
    try {
        const module = await import(`./pages/${modulePath}.js`);
        return module.default;
    } catch (error) {
        console.error(`모듈 로드 실패: ${modulePath}`, error);
        return {};
    }
};

// Alpine.js에 자동 로딩 기능 등록
Alpine.data('autoLoad', () => ({
    async init() {
        // data-auto-load 속성에서 모듈 경로 가져오기
        const modulePath = this.$el.getAttribute('data-auto-load');
        if (modulePath) {
            const module = await loadModule(modulePath);
            // 모듈의 init 함수가 있다면 실행
            if (typeof module.init === 'function') {
                module.init.call(this);
            }
            // 모듈의 데이터를 현재 컴포넌트에 병합
            Object.assign(this, module);
        }
    }
}));

window.Alpine = Alpine;
Alpine.start();
