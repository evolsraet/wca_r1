// utils.js
export function setRandomPlaceholder() {
    const placeholders = [
        "더 뉴 그랜저",
        "현대 아반떼",
        "기아 쏘렌토",
        "쌍용 티볼리",
        "르노삼성 QM6",
        "쉐보레 트랙스",
        "현대 투싼",
        "기아 스포티지",
        "쌍용 렉스턴",
        "르노삼성 SM6",
        "쉐보레 말리부",
        "BMW 3시리즈",
        "벤츠 E클래스",
        "아우디 A4",
        "폭스바겐 티구안",
        "포드 익스플로러",
        "제네시스 G70",
        "현대 펠리세이드",
        "기아 K5",
        "쌍용 코란도",
        "르노삼성 XM3",
        "쉐보레 이쿼녹스",
        "테슬라 모델 3",
        "현대 코나",
        "기아 니로",
        "쌍용 액티언",
        "르노삼성 캡처",
        "쉐보레 콜로라도",
        "BMW X5",
        "벤츠 GLC",
        "아우디 Q5",
        "폭스바겐 골프",
        "포드 머스탱",
        "제네시스 G80",
        "현대 싼타페",
        "기아 셀토스",
        "쌍용 무쏘",
        "르노삼성 클리오",
        "쉐보레 임팔라",
        "BMW 5시리즈",
        "벤츠 S클래스",
        "아우디 A6",
        "폭스바겐 파사트",
        "포드 F-150",
        "제네시스 GV80"
    ];

    const emojis = ["🚗", "🚙", "🚐", "🚕", "🚖", "🚘", "🚍", "🚔", "🚓", "🚑", "🚒", "🚚"];

    const randomCarIndex = Math.floor(Math.random() * placeholders.length);
    const randomEmojiIndex = Math.floor(Math.random() * emojis.length);

    const carName = placeholders[randomCarIndex];
    const emoji = emojis[randomEmojiIndex];

    const searchInput = document.getElementById('search-input');
    if (searchInput) {
        searchInput.placeholder = carName;
    }



    return { carName, emoji };
}
