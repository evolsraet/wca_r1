import { appendFormData, appendFilesToFormData, setupFileUploadListeners } from '../../util/fileUpload';

export default function () {
    return {
        _taksongDay: 3,
        selectedDay: null,
        selectedTime: null,
        days: [],
        morningTimes: ['9:00', '9:30', '10:00', '10:30', '11:00', '11:30'],
        afternoonTimes: ['13:00', '13:30', '14:00', '14:30', '15:00', '15:30', '16:00', '16:30', '17:00', '17:30', '18:00'],
        // TODO: isBeyond 에서 체크하지만 하드코딩으로 입력됨. 테스트 용인지 불분명
        // taksongEndAt: new Date('2025-06-30T18:00:00'), // 필요시 주입 가능
        taksongEndAt: null, // 필요시 주입 가능
        holidays: window.holidays ?? [],
        selectedDate: null,
        bid: null,
        loading: true,
        data: {},
        form: {
            auction: {}
        },
        errors: {},
        init() {
            this.days = [];
            this.data = window.modalData.content.data;

            const auction = window.modalData.content.auction;

            console.log('dealerDeliveryChoice init', this.data);
            this.form.auction = auction;

            const baseDate = new Date();
            baseDate.setDate(baseDate.getDate() + 1); // 오늘 +1 (익일)

            const weekDays = ['일', '월', '화', '수', '목', '금', '토'];
            const originalBase = new Date(baseDate); // 이거 기준으로 계산

            // TODO: 일수 ENV 사용필요
            for (let i = 0; i < this._taksongDay; i++) {
                const date = new Date(originalBase); // 항상 원본 기준으로 복사
                date.setDate(originalBase.getDate() + i); // i일 추가

                const ymd = date.toISOString().split('T')[0];
                const isHoliday = this.holidays.includes(ymd);

                this.days.push({
                    date,
                    label: `${date.getDate()} (${weekDays[date.getDay()]})`,
                    isHoliday,
                    ymd
                });
            }
            
        },
        bankSelector() {
            Alpine.store(`modal`).showHtmlFromUrl('/v2/components/modals/bankSelectorModal', {
                id: 'bankSelectorModal',
                title: '은행 선택',
                showFooter: false
            });
        },

        formatDate(date) {
            return date.toISOString().split('T')[0];
        },
    
        formatSelectedDate() {
            if (this.selectedDay === null) return '';
            const date = this.days[this.selectedDay].date;
            const yyyy = date.getFullYear();
            const mm = String(date.getMonth() + 1).padStart(2, '0');
            const dd = String(date.getDate()).padStart(2, '0');
            return `${yyyy}-${mm}-${dd}`;
        },
    
        selectDay(index) {
            this.selectedDay = index;
            this.selectedTime = null;
        },
    
        selectTime(time) {
            if (!this.isDisabled(time)) {
                this.selectedTime = time;

                this.selectedDate = this.formatSelectedDate() + ' ' + time;
            }
        },
    
        isDisabled(time) {
            return this.isPastTime(time) || this.isBeyondEndTime(time);
        },
    
        isPastTime(time) {
            const [h, m] = time.split(':').map(Number);
            const now = new Date();
            const selectedDate = new Date(this.days[this.selectedDay].date);
            selectedDate.setHours(h, m, 0, 0);
            return this.selectedDay === 0 && selectedDate < now;
        },
    
        // 선택한 시간이 탁송 가능 종료 시각(taksongEndAt)을 초과하는지 확인하는 함수
        isBeyondEndTime(time) {
            if(!this.taksongEndAt) return false;

            const [h, m] = time.split(':').map(Number);
            const selectedDate = new Date(this.days[this.selectedDay].date);
            selectedDate.setHours(h, m, 0, 0);
            return selectedDate > this.taksongEndAt;
        },

        currentMonthText() {
            const baseDate = this.selectedDay !== null
              ? this.days[this.selectedDay].date
              : new Date();
          
            const month = baseDate.getMonth() + 1;
            return `${month}월`;
        },

        submit() {
            console.log('submit');

            if(!this.selectedDate) {
                Alpine.store('swal').fire({
                    title: '탁송일시를 선택해주세요.',
                    icon: 'error',
                    confirmButtonText: '확인'
                });
                return;
            }

            // console.log('this.form.auction?!?@?', this.form.auction);

            const formElements = this.$el.elements;

            const formData = {
                account: formElements['auction.account']?.value || '',
                bank: formElements['auction.bank']?.value || '',
                addr_post: formElements['auction.addr_post']?.value || '',
                addr1: formElements['auction.addr1']?.value || '',
                addr2: formElements['auction.addr2']?.value || '',
                customTel1: formElements['auction.customTel1']?.value || '',
                customTel2: formElements['auction.customTel2']?.value || '',
                selectedDate: this.selectedDate,
            };

            Alpine.store('modal').showHtmlFromUrl('/v2/components/modals/dealerDeliveryConfirm', {
                id: 'dealerDeliveryConfirm',
                title: '탁송정보 확인',
                size: 'modal-dialog-centered',
                showFooter: false,
            }, {
                content: {
                    auction: {
                        ...this.form.auction, // 기존에 있던 auction 정보
                        ...formData           // form 입력값 병합
                    }
                }
            });

            Alpine.store('modal').close('dealerDeliveryChoice');
        },

        openDocModal(isBizCheck) {

            console.log('openDocModalauction', isBizCheck);

            let file = 'carSaleDoc';
            let title = '매도용 인감증명서 안내 (일반용)';
            if(isBizCheck === '1'){
                file = 'carSaleDocBusiness';
                title = '매도용 인감증명서 안내 (사업자용)';
            }

            Alpine.store('modal').showHtmlFromUrl('/v2/docs/' + file + '?raw=1', {
                id: 'docModal',
                size: 'modal-dialog-centered ',
                footerButtons: [{ text: '닫기', class: 'btn-secondary', dismiss: true }],
                title: title,
            });
        },

        closeModal() {
            Alpine.store('modal').close('dealerDeliveryChoice');
        }
    };
}