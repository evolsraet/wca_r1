export default function () {
    return {
        selectedTab: 'bank',
        banks: [
            { name: '하나', image: '/images/bank-img/hana-logo.png' },
            { name: '국민', image: '/images/bank-img/kb-logo.png' },
            { name: '우리', image: '/images/bank-img/woori-logo.png' },
            { name: '신한', image: '/images/bank-img/shinhan-logo.png' },
            { name: '농협', image: '/images/bank-img/nonghyeop.png' },
            { name: '카카오뱅크', image: '/images/bank-img/kakaobank.png' },
            { name: '토스뱅크', image: '/images/bank-img/toss.png' },
            { name: '기업', image: '/images/bank-img/ibk-logo.png' },
            { name: '대구', image: '/images/bank-img/daegu-logo.png' },
            { name: '부산', image: '/images/bank-img/bnk.png' },
            { name: '경남', image: '/images/bank-img/bnk.png' },
            { name: '광주', image: '/images/bank-img/gwangju.png' },
            { name: '전북', image: '/images/bank-img/gwangju.png' }, // 이미지가 동일한 경우 일단 재사용
            { name: '제주', image: '/images/bank-img/shinhan-logo.png' }, // 이미지 대체
            { name: '산업', image: '/images/bank-img/sanup.png' },
            { name: '수협', image: '/images/bank-img/suhyeop.png' },
            { name: '한국씨티', image: '/images/bank-img/ssiti.png' },
            { name: 'SC제일', image: '/images/bank-img/jeil.png' },
            { name: 'hsbc', image: '/images/bank-img/hsbc.png' },
        ],
        stocks: [
            { name: '토스증권', image: '/images/bank-img/toss.png' },
            { name: '카카오증권', image: '/images/bank-img/kakaopay.png' },
            { name: '미래에셋', image: '/images/bank-img/miraeeset .png' }, // 공백 제거 필요
            { name: '키움', image: '/images/bank-img/kium.png' },
            { name: '한국투자', image: '/images/bank-img/hanguktuja .png' }, // 공백 제거 필요
            { name: '신한투자', image: '/images/bank-img/shinhan-logo.png' },
            { name: '삼성증권', image: '/images/bank-img/samsung.png' },
            { name: 'KB증권', image: '/images/bank-img/kb-logo.png' },
            { name: 'NH투자', image: '/images/bank-img/nonghyeop.png' },
            { name: '유안타', image: '/images/bank-img/yuan.png' },
            { name: '대신', image: '/images/bank-img/daesin.png' },
            { name: 'IBK투자', image: '/images/bank-img/ibk-logo.png' },
            { name: '하나증권', image: '/images/bank-img/hana-logo.png' },
            { name: 'DB금융투자', image: '/images/bank-img/db.png' },
            { name: 'BNK투자', image: '/images/bank-img/bnk.png' },
        ],
        init() {
            this.showTab(this.selectedTab);
        },
        selectBank(bankName) {
            const input = document.querySelector('[name="auction.bank"]');
            if (input) {
                input.value = bankName;
            }

            const label = document.getElementById('selected_bank_label');
            if (label) {
                label.innerText = '선택된 은행: ' + bankName;
            }

            console.log('bankSelect ? ', bankName);

            window.modalData?.result?.({
                bank: bankName
            });

            Alpine.store('modal').close('bankSelectorModal');

            const accountInput = document.getElementById('auction.account');
            if (accountInput) accountInput.focus();
        },
        showTab(tabId) {
            // console.log(tabId);
            this.selectedTab = tabId;
        }
    };
}