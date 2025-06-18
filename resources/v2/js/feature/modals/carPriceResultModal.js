export default function () {
    return {
        estimatedPriceInTenThousandWon: 0,
        init() {
            const data = window.modalData.content;
            console.log(data);

            this.estimatedPriceInTenThousandWon = data.estimatedPriceInTenThousandWon;
        },
        submit() {

            window.modalData?.onResult?.({
                estimatedPriceInTenThousandWon: this.estimatedPriceInTenThousandWon
            });
            
            localStorage.setItem('estimatedPrice', JSON.stringify({
                value: this.estimatedPriceInTenThousandWon,
                carNo: window.modalData.content.carNo,
                timestamp: Date.now()
            }));

            document.getElementById('estimate-price').style.display = 'block';
            document.getElementById('estimate-price-text').style.display = 'none';
            document.getElementById('estimatedPriceInTenThousandWon').textContent = this.estimatedPriceInTenThousandWon + ' 만원';
            
            Alpine.store(`modal`).close('carPriceResultModal');
        },
        reset() {
            window.modalData.content.estimatedPriceInTenThousandWon = '';

            localStorage.removeItem('estimatedPrice');
            document.getElementById('estimatedPriceInTenThousandWon').textContent = '';

            document.getElementById('estimate-price').style.display = 'none';
            document.getElementById('estimate-price-text').style.display = 'block';

            Alpine.store(`modal`).close('carPriceResultModal');
            
            Alpine.store(`modal`).showHtmlFromUrl('/v2/components/modals/currentPrice', {
                id: 'currentPrice',
                title: '내 차, 예상가격을 확인합니다',
                showFooter: false,
            }, {
                content: {
                    carInfo: window.carInfo
                },
                onResult: (result) => {
                    console.log('result?', result);
                }
            });
        }
    }
}