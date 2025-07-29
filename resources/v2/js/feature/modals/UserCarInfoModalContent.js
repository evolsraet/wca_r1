import { Alert } from "bootstrap";

export default function () {
    return {
        cars: window.userCarInfo,
        init() {
            console.log('userCarInfoModalContent init');
            console.log('cars data:', this.cars);
        },
        closeModal() {
            Alpine.store(`modal`).close('userCarInfoModalContent');
            document.querySelector('input[name="owner"]').focus();
        },
        async deleteCar(car) {
            console.log('삭제할 차량:', car);
            
            // 삭제 확인
            if (!confirm(`${car.owner}님의 차량 ${car.no}을(를) 삭제하시겠습니까?`)) {
                return;
            }
            
            try {
                // 백엔드 API 호출로 서버에서 삭제
                const response = await Alpine.store('api').delete('/api/auctions/carInfo/user', {
                    owner: car.owner,
                    no: car.no
                });

                if (response.data.status === 'ok') {
                    // cars 배열에서 해당 차량 제거
                    const index = this.cars.findIndex(c => c.owner === car.owner && c.no === car.no);
                    if (index !== -1) {
                        this.cars.splice(index, 1);
                        console.log('차량 삭제 후 cars:', this.cars);
                        
                        // 삭제 완료 메시지
                        Alpine.store('toastr').success('차량 정보가 삭제되었습니다.');
                        
                        // 모든 차량이 삭제되었으면 모달 닫기
                        if (this.cars.length === 0) {
                            setTimeout(() => {
                                this.closeModal();
                            }, 1000);
                        }
                    }
                } else {
                    Alpine.store('toastr').error(response.data.message || '삭제에 실패했습니다.');
                }
                
            } catch (error) {
                console.error('차량 삭제 API 오류:', error);
                if (error.response?.data?.message) {
                    Alpine.store('toastr').error(error.response.data.message);
                } else {
                    Alpine.store('toastr').error('차량 정보 삭제 중 오류가 발생했습니다.');
                }
            }
        },
        selectCar(car) {
            console.log('선택된 차량:', car);

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/v2/sell/result';

            // CSRF 토큰 추가
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