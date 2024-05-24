import axios from 'axios';
import { reactive , ref } from 'vue';
let starScore = 0;

export function initReviewSystem() {

    const review = reactive({
            content: "",
            user_id: "",    
            auction_id: "",
            dealer_id: "",
            star: ""

    })
    const reviewForm = {
        review
    }
    const reviewsData = ref([]);

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
                        starScore = idx + 1;
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
    
    function setReviewValue(user_id, auction_id, dealer_id, star){
        review.user_id = user_id;
        review.auction_id = auction_id;
        review.dealer_id = dealer_id;
        review.star = star;
        //console.log(JSON.stringify(reviewForm));
    }

    // 리뷰 작성 post
    const submitReview = async () => {
        try {      
            const user_id = document.getElementById('user_id').value;
            const auction_id = document.getElementById('auction_id').value;
            const dealer_id = document.getElementById('dealer_id').value;
            console.log(user_id);
            console.log(auction_id);
            console.log(dealer_id);
            console.log(JSON.stringify(reviewForm));
            if(auction_id == "" || dealer_id == ""){
                alert("오류가 발생하였습니다. 관리자에게 문의해주세요.");
                return;
            }
            if(starScore == 0 && starScore == ""){
                alert("별점을 입력해주세요.");
                return;
            }
            setReviewValue(user_id, auction_id, dealer_id, starScore);
            console.log(JSON.stringify(reviewForm));
            const response = await axios.post("/api/reviews", reviewForm);
            
            //console.log(response);

            if(response.data.status == 'ok'){
                alert("작성이 완료되었습니다.");
                location.href = "/alllist-do";
            }
            
        } catch (error) {
            alert("오류가 발생하였습니다. 관리자에게 문의해주세요2.");
            console.log(error);
        }
    }
    
    // 작성한 이용후기 삭제하기
    const deleteReviewApi = async (id) => {
        try {      
            const response = await axios.delete(`/api/reviews/${id}`);
            //console.log(response);
            //console.log(reviewsData.value);
        } catch (error) {
            console.log(error);
        }
    }
    /** 
    //작성한 이용후기 수정하기
    const editReviewApi = async (id) => {
        try {
            const response = await axios.
        } catch(error){

        }
    }
    **/
   
    // 작성한 이용후기 불러오기 (사용자별 불러오기)
    const getUserReview = async (id) => {
        try {      
            const response = await axios.get("/api/reviews");
            reviewsData.value  = response.data.data.filter(review => review.user_id === id); 
            if(response.status === '204'){
                return true;
            } else{
                return false;
            }
            //console.log(reviewsData.value);
        } catch (error) {
            console.log(error);
        }
    }
    
    //작성한 이용후기별 불러오기
    const getUserReviewInfo = async (id) => {
        try {      
            const response = await axios.get(`/api/reviews/${id}`);
            //console.log(response);
            return response.data.data;
        } catch (error) {
            console.log(error);
        }
    }

    // 작성한 이용후기 불러오기 (전체 리뷰 불러오기)
    const getAllReview = async () => {
        try {      
            const response = await axios.get("/api/reviews");
            return response.data;
        } catch (error) {
            console.log(error);
        }
    }


    function formattedAmount(amount) {
        let amountInThousands = Math.floor(amount / 10000); 
        let formattedAmount = amountInThousands.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        return formattedAmount + '만원';
    }

    return {
        review,
        submitReview,
        getUserReview,
        getAllReview,
        deleteReviewApi,
        getUserReviewInfo,
        formattedAmount,
        reviewsData
    }

}