export function initReviewSystem() {
    const rateWrap = document.querySelectorAll('.rating'),
        label = document.querySelectorAll('.rating .rating__label'),
        input = document.querySelectorAll('.rating .rating__input'),
        labelLength = label.length,
        opacityHover = '0.5';

    let stars = document.querySelectorAll('.rating .star-icon');

checkedRate();


rateWrap.forEach(wrap => {
    const scoreDisplay = wrap.querySelector('.rating-score'); // 점수를 표시할 span 요소
    wrap.addEventListener('mouseenter', () => {
        stars = wrap.querySelectorAll('.star-icon');

        stars.forEach((starIcon, idx) => {
            starIcon.addEventListener('mouseenter', () => {
                if (wrap.classList.contains('readonly') == false) {
                    initStars(); // 기선택된 별점 무시하고 초기화
                    filledRate(idx, labelLength);  // hover target만큼 별점 active

                    // hover 시 active된 별점의 opacity 조정
                    for (let i = 0; i < stars.length; i++) {
                        if (stars[i].classList.contains('filled')) {
                            stars[i].style.opacity = opacityHover;
                        }
                    }
                }
            });

            starIcon.addEventListener('mouseleave', () => {
                if (wrap.classList.contains('readonly') == false) {
                    starIcon.style.opacity = '1';
                    checkedRate(); // 체크된 라디오 버튼 만큼 별점 active
                }
            });

            starIcon.addEventListener('click', () => {
                if (!wrap.classList.contains('readonly')) {
                   // console.log("Clicked star index:", idx); // 클릭된 별의 인덱스 로깅
                    //console.log("Score display element:", scoreDisplay); // 점수 표시 요소 로깅
                    scoreDisplay.textContent = `( ${idx + 1} 점 )`; // 점수 표시
                }
            });
            
            // rate wrap을 벗어날 때 active된 별점의 opacity = 1
            wrap.addEventListener('mouseleave', () => {
                if (wrap.classList.contains('readonly') == false) {
                    starIcon.style.opacity = '1';
                }
            });

            // readonly 일 때 비활성화
            wrap.addEventListener('click', (e) => {
                if (wrap.classList.contains('readonly')) {
                    e.preventDefault();
                }
            });
        });
    });
});

// target보다 인덱스가 낮은 .star-icon에 .filled 추가 (별점 구현)
function filledRate(index, length) {
    if (index <= length) {
        for (let i = 0; i <= index; i++) {
            stars[i].classList.add('filled');
        }
    }
}

// 선택된 라디오버튼 이하 인덱스는 별점 active
function checkedRate() {
    let checkedRadio = document.querySelectorAll('.rating input[type="radio"]:checked');


    initStars();
    checkedRadio.forEach(radio => {
        let previousSiblings = prevAll(radio);

        for (let i = 0; i < previousSiblings.length; i++) {
            previousSiblings[i].querySelector('.star-icon').classList.add('filled');
        }

        radio.nextElementSibling.classList.add('filled');

        function prevAll() {
            let radioSiblings = [],
                prevSibling = radio.parentElement.previousElementSibling;

            while (prevSibling) {
                radioSiblings.push(prevSibling);
                prevSibling = prevSibling.previousElementSibling;
            }
            return radioSiblings;
        }
    });
}

// 별점 초기화 (0)
function initStars() {
    for (let i = 0; i < stars.length; i++) {
        stars[i].classList.remove('filled');
    }
}

}