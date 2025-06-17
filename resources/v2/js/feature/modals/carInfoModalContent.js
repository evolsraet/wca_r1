export default function () {
    return {
        cars: [],
        init() {
            console.log('CarInfoModalContent');
            const data = window.modalOptions.data;
            // console.log('window.cars', data.cars);

            this.cars = JSON.parse(JSON.stringify(data.cars));
        },
        goToWrite(car) {
            // console.log('goToWrite', car);
            window.location.href = `/v2/board/${window.boardConfig.boardId}/form?id=${car}`;
        }
    };
}