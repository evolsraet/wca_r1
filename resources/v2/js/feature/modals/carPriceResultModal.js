export default function () {
    return {
        estimatedPriceInTenThousandWon: 0,
        init() {
            const data = window.modalOptions.data;
            console.log(data);

            this.estimatedPriceInTenThousandWon = data.estimatedPriceInTenThousandWon;
        },
        submit() {

            window.modalOptions?.onResult?.({
                estimatedPriceInTenThousandWon: this.estimatedPriceInTenThousandWon
            });
            
            localStorage.setItem('estimatedPrice', JSON.stringify({
                value: this.estimatedPriceInTenThousandWon,
                carNo: window.modalOptions.data.carNo,
                timestamp: Date.now()
            }));

            document.getElementById('estimatedPriceInTenThousandWon').textContent = this.estimatedPriceInTenThousandWon;
            
            Alpine.store(`modal`).close('carPriceResultModal');
        },
        reset() {
            window.modalOptions.data.estimatedPriceInTenThousandWon = 0;

            localStorage.removeItem('estimatedPrice');
            document.getElementById('estimatedPriceInTenThousandWon').textContent = 0;

            Alpine.store(`modal`).close('carPriceResultModal');
            
            Alpine.store(`modal`).showHtmlFromUrl('/v2/components/modals/currentPrice', {
                id: 'currentPrice',
                title: '내 차, 예상가격을 확인합니다',
                showFooter: false,
                data: {
                    carInfo: window.carInfo
                },
                onResult: (result) => {
                    console.log('result?', result);
                }
            });
        }
    }
}