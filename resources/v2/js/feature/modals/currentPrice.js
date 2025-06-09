import { api } from '../../util/axios';

export default function () {
    return {
        carInfo: {
            'km' : '',
            'firstRegDate' : '',
            'priceNowWhole' : '',
            'priceNow' : '',
            'estimatedPriceInTenThousandWon' : '',
            'accident' : '완전 무사고',
            'keyCount' : 0,
            'wheelScratch' : 0,
            'tireStatusReplaced' : 0,
            'viewPaint' : 0,
            'viewChange' : 0,
            'viewBreak' : 0
        },
        init() {
            const data = window.modalOptions.data;

            this.carInfo.km = data.carInfo.car_km;
            this.carInfo.firstRegDate = data.carInfo.car_first_reg_date;
            this.carInfo.priceNowWhole = data.carInfo.car_price_now_whole;
            this.carInfo.priceNow = data.carInfo.car_price_now;

        },
        async getCarInfo() {

            const firstRegDate = this.carInfo.firstRegDate.split('-');
            const regYear = firstRegDate[0];
            const regMonth = firstRegDate[1];
            const currentMileage = this.carInfo.km;
            const initialPrice = this.carInfo.priceNowWhole;

            const accident = this.carInfo.accident;
            const keyCount = this.carInfo.keyCount;
            const wheelScratch = this.carInfo.wheelScratch;
            const tireStatusReplaced = this.carInfo.tireStatusReplaced;
            const viewPaint = this.carInfo.viewPaint;
            const viewChange = this.carInfo.viewChange;
            const viewBreak = this.carInfo.viewBreak;

            const response = await api.get('/api/depreciation/calculate', {
                regYear: regYear,
                regMonth: regMonth,
                currentMileage: currentMileage,
                initialPrice: initialPrice,
                accident: accident,
                keyCount: keyCount,
                wheelScratch: wheelScratch,
                tireStatusReplaced: tireStatusReplaced,
                viewPaint: viewPaint,
                viewChange: viewChange,
                viewBreak: viewBreak
            });

            // 여기서 response.data.estimatedPriceInTenThousandWon 값들어온거 확인. 

            this.carInfo.estimatedPriceInTenThousandWon = response.data.estimatedPriceInTenThousandWon;
        },
        
        async clickCheckPrice() {
            const data = window.modalOptions.data;
            await this.getCarInfo();
            
            Alpine.store(`modal`).close('currentPrice');

            // 여기서 예상 가격이 안찍힘... 
            console.log('예상 가격', this.carInfo.estimatedPriceInTenThousandWon);

            Alpine.store(`modal`).showHtmlFromUrl('/v2/components/modals/carPriceResultModal', {
                id: 'carPriceResultModal',
                title: '예상 가격',
                showFooter: false,
                data: {
                    estimatedPriceInTenThousandWon: this.carInfo.estimatedPriceInTenThousandWon,
                    carNo: data.carInfo.car_no
                }
            });

        }
    };
}
