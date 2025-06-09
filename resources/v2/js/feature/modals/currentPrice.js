import { api } from '../../util/axios';

export default function () {
    return {
        carInfo: [
            'km',
            'firstRegDate',
            'priceNowWhole',
            'priceNow',
            'maker',
            'model',
            'year',
        ],
        init() {

            const data = window.modalOptions.data;
            console.log(data);

            this.carInfo.km = data.carInfo.car_km;

        },
        async getCarInfo() {
            const response = await api.get('/api/depreciation/calculate', {
                regYear: '2011',
                regMonth: '02',
                currentMileage: '100000',
                initialPrice: '10000000'
            });

            this.carInfo = response;
        },
        
        clickCheckPrice() {
            // this.getCarInfo();
            
            Alpine.store(`modal`).close('currentPrice');

            Alpine.store(`modal`).showHtmlFromUrl('/v2/components/modals/CarPriceResultModal', {
                id: 'carPriceResultModal',
                title: '예상 가격',
                showFooter: false,
                data: {
                    carInfo: this.carInfo
                }
            });

        }
    };
}
