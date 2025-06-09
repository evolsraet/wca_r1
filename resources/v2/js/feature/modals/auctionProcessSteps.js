export default function () {
    return {
        init() {
            // console.log('auctionProcessSteps');
        },
        submit() {
            console.log('submit');

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/v2/sell/apply';

            // CSRF 토큰 추가 (Laravel 사용하는 경우 필수)
            const token = document.querySelector('meta[name="csrf-token"]')?.content;
            if (token) {
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = token;
            form.appendChild(csrfInput);
            }

            if (window.carInfo) {
            Object.entries(window.carInfo).forEach(([key, value]) => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = value;
                form.appendChild(input);
            });
            }

            Alpine.store('modal').close('auctionProcessSteps');

            // 폼 삽입 후 전송
            document.body.appendChild(form);
            form.submit();
        }
    };
}
