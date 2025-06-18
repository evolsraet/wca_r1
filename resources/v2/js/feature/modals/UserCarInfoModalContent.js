export default function () {
    return {
        cars: window.userCarInfo,
        init() {
            console.log('userCarInfoModalContent init');
        },
        closeModal() {
            Alpine.store(`modal`).close('userCarInfoModalContent');

            document.querySelector('input[name="owner"]').focus();
        },
        selectCar(car) {
            console.log(car);

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/v2/sell/result';

            // CSRF 토큰 추가 (Laravel 사용하는 경우 필수)
            const token = document.querySelector('meta[name="csrf-token"]')?.content;
            if (token) {
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = token;
            form.appendChild(csrfInput);
            }

            if (car) {
            Object.entries(car).forEach(([key, value]) => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = value;
                form.appendChild(input);
            });
            }

            Alpine.store('modal').close('userCarInfoModalContent');

            // 폼 삽입 후 전송
            document.body.appendChild(form);
            form.submit();

        }
    };
}