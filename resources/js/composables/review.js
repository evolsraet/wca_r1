import axios from 'axios';
import { reactive , ref , inject } from 'vue';
import useAuctions from "./auctions";
import { useRouter } from 'vue-router';
let starScore = 0;

export function initReviewSystem() {
    const router = useRouter();;
    const swal = inject('$swal');
    const { getAuctionById, AuctionCarInfo } = useAuctions();
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
    const pagination = ref({});

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
    
    //작성 리뷰 불러올 때 별점 뿌리기(수정,더보기)
    const setInitialStarRating = (starRating) => {
        const stars = document.querySelectorAll('.rating__input'); 
        const scoreDisplay = document.querySelector('.rating-score'); // 점수를 표시할 span 요소
        scoreDisplay.textContent = `( ${starRating} 점 )`; 

        const reviewStarValue = document.getElementById('reviewStarValue');
        if(reviewStarValue){
            reviewStarValue.value = starRating;
        }
        const starDescription = document.querySelectorAll('.rating-description');
        stars.forEach(star => {
            if (parseInt(star.value) === starRating) {
                star.checked = true;
            }
        });
        starDescription.forEach(des => {
            const value = des.getAttribute('value');
            switch (value) {
                case '1':
                    des.textContent = '그저그래요';
                    break;
                case '2':
                    des.textContent = '괜찮아요';
                    break;
                case '3':
                    des.textContent = '좋아요';
                    break;
                case '4':
                    des.textContent = '만족해요';
                    break;
                case '5':
                    des.textContent = '최고에요!';
                    break;      
            }
        })
    }

    function setReviewValue(user_id, auction_id, dealer_id, star, content){
        review.content = content;
        review.user_id = user_id;
        review.auction_id = auction_id;
        review.dealer_id = dealer_id;
        review.star = star;
        //console.log(JSON.stringify(reviewForm));
    }

    // 리뷰 작성 post
    const submitReview = async () => {
        try {     
            const content = document.getElementById('content').value; 
            const user_id = document.getElementById('user_id').value;
            const auction_id = document.getElementById('auction_id').value;
            const dealer_id = document.getElementById('dealer_id').value;
            
            console.log(JSON.stringify(reviewForm));
            if(auction_id == "" || dealer_id == ""){
                alert("오류가 발생하였습니다. 관리자에게 문의해주세요.");
                return;
            }
            if(starScore == 0 && starScore == ""){
                alert("별점을 입력해주세요.");
                return;
            }
            setReviewValue(user_id, auction_id, dealer_id, starScore, content);
            console.log(JSON.stringify(reviewForm));
            const response = await axios.post("/api/reviews", reviewForm);
            
            //console.log(response);

            if(response.data.status == 'ok'){
                alert("작성이 완료되었습니다.");
                location.href = "/list-do";
            }
            
        } catch (error) {
            alert("오류가 발생하였습니다. 관리자에게 문의해주세요.");
            console.log(error);
        }
    }
    
    // 작성한 이용후기 삭제하기
    const deleteReviewApi = async (id) => {
        swal({
            title: 'Are you sure?',
            text: 'You won\'t be able to revert this action!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            confirmButtonColor: '#ef4444',
            timer: 20000,
            timerProgressBar: true,
            reverseButtons: true
        })
            .then(result => {
                if (result.isConfirmed) {
                    axios.delete(`/api/reviews/${id}`)
                        .then(response => {
                            getAllReview()
                            router.push({name: 'deposit.index'})
                            swal({
                                icon: 'success',
                                title: 'review deleted successfully'
                            })
                        })
                        .catch(error => {
                            console.log(error);
                            swal({
                                icon: 'error',
                                title: 'Something went wrong'
                            })
                        })
                }
            })

    }
     
    //작성한 이용후기 수정하기
    const editReview = async (id) => {
        console.log(id);
        if (confirm("수정하시겠습니까?")){
            try {     
                const content = document.getElementById('content').value; 
                const user_id = document.getElementById('user_id').value;
                const auction_id = document.getElementById('auction_id').value;
                const dealer_id = document.getElementById('dealer_id').value;
                const star = document.getElementById('reviewStarValue').value;
    
                console.log(JSON.stringify(reviewForm));
    
                if(auction_id == "" || dealer_id == ""){
                    alert("오류가 발생하였습니다. 관리자에게 문의해주세요.");
                    return;
                }
                if(starScore == 0){ //별점을 수정(클릭)하지 않은 경우
                    starScore = star;
                }
    
                setReviewValue(user_id, auction_id, dealer_id, starScore, content);
                console.log(JSON.stringify(reviewForm));
                const response = await axios.put(`/api/reviews/${id}`, reviewForm);
                
                console.log(response);
    
                if(response.data.status == 'ok'){
                    alert("수정이 완료되었습니다.");
                    location.href = "/list-do";
                }
                
            } catch (error) {
                alert("오류가 발생하였습니다. 관리자에게 문의해주세요.");
                console.log(error);
            }
        }
    }

   
    // 작성한 이용후기 불러오기 (사용자별 불러오기)
    const getUserReview = async (id) => {
        try {      
            const response = await axios.get("/api/reviews");
            reviewsData.value  = response.data.data.filter(review => review.user_id === id); 
            for (const review of reviewsData.value) {
                const auction = await getAuctionById(review.auction_id);
                review.auction = auction.data;
            }
            console.log(reviewsData.value);
            if(response.status === '204'){
                return true;
            } else{
                return false;
            }
            
        } catch (error) {
            console.log(error);
        }
    }
    
    //작성한 이용후기별 불러오기
    const getUserReviewInfo = async (id) => {
        try {      
            const response = await axios.get(`/api/reviews/${id}`);
            const data = response.data.data;
            const auction = await getAuctionById(data.auction_id);
            data.auction = auction.data;
            return data;
        } catch (error) {
            console.log(error);
        }
    }

    // 작성한 이용후기 불러오기 (전체 리뷰 불러오기)
    const getAllReview = async (page = 1) => {
        try {      
            const response = await axios.get(`/api/reviews?page=${page}`);
            reviewsData.value = response.data.data;
            pagination.value = response.data.meta;
            console.log('Pagination:', pagination.value);
            for (const review of reviewsData.value) {
                const auction = await getAuctionById(review.auction_id);
                review.auction = auction.data;
            }
        } catch (error) {
            console.log(error);
        }
    }


    function formattedAmount(amount) {
        let amountInThousands = Math.floor(amount / 10000); 
        let formattedAmount = amountInThousands.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        return formattedAmount + '만원';
    }

    function splitDate (date){
        return date.split(' ')[0];
    }
    
    const getCarInfo = async (owner, no) => {
        try{
            const carInfoForm = {
                owner: owner,
                no: no,
                forceRefresh: ""
            };
            const carInfoResponse = await AuctionCarInfo(carInfoForm);
            return carInfoResponse.data;
        } catch (error){

        }
        
    }

   

    return {
        review,
        pagination,
        submitReview,
        getUserReview,
        getAllReview,
        deleteReviewApi,
        editReview,
        getUserReviewInfo,
        formattedAmount,
        splitDate,
        reviewsData,
        getCarInfo,
        setInitialStarRating
    }

}