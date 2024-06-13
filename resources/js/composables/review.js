import axios from 'axios';
import { reactive , ref , inject } from 'vue';
import useAuctions from "./auctions";
import { useRouter } from 'vue-router';
import { cmmn } from '@/hooks/cmmn';

let starScore = 0;

export function initReviewSystem() {
    const { callApi } = cmmn();
    const router = useRouter();


    const swal = inject('$swal');
    const { getAuctionById, AuctionCarInfo } = useAuctions();
    const review = reactive({
            content: "",
            user_id: "",    
            auction_id: "",
            dealer_id: "",

    })
    const reviewForm = {
        review
    }
    const reviewsData = ref([]);
    const reviewPagination = ref({});
    
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
    const submitReview = async (review) => {
        review.star = starScore;
        console.log(review);
        if(review.auction_id == "" || review.dealer_id == ""){
            alert("오류가 발생하였습니다. 관리자에게 문의해주세요.");
            return;
        }
        if(starScore == 0 && starScore == ""){
            alert("별점을 입력해주세요.");
            return;
        }

        const form = {
            review
        }
        console.log(JSON.stringify(form));
        swal({
            title: '이용후기를 작성하시겠어요?',
            text: '',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '작성',
            cancelButtonText: '취소',
            confirmButtonColor: '#ef4444',
            timer: 20000,
            timerProgressBar: true,
            reverseButtons: true
        })
        .then(result => {
            if (result.isConfirmed) {
                axios.post(`/api/reviews`, form)
                    .then(response => {
                        swal({
                            icon: 'success',
                            title: '이용후기가 정상적으로 작성되었습니다.',
                        }).then(() => {
                            router.push({name: 'user.review'});
                        });
                        
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
    
    // 작성한 이용후기 삭제하기
    const deleteReviewApi = async (id) => {
        swal({
            title: '정말 삭제하시겠어요?',
            text: '삭제하시면 이용후기를 다시 작성할 수 없습니다.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '삭제',
            cancelButtonText: '취소',
            confirmButtonColor: '#ef4444',
            timer: 20000,
            timerProgressBar: true,
            reverseButtons: true
        })
        .then(result => {
            if (result.isConfirmed) {
                axios.delete(`/api/reviews/${id}`)
                    .then(response => {
                        swal({
                            icon: 'success',
                            title: '이용후기가 정상적으로 삭제되었습니다.',
                        }).then(() => {
                            router.push({name: 'user.review'});
                            location.reload();
                        });
                        
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
     
    //작성한 이용후기 수정하기 - 사용자
    const editReview = async (id, review) => {

        if(starScore > 0){
            review.star = starScore;
        }
   
        const form = {
            review
        }

        swal({
            title: '정말 수정하시겠어요?',
            text: '',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '수정',
            cancelButtonText: '취소',
            confirmButtonColor: '#ef4444',
            timer: 20000,
            timerProgressBar: true,
            reverseButtons: true
        })
        .then(result => {
            if (result.isConfirmed) {
                axios.put(`/api/reviews/${id}`, form)
                    .then(response => {
                        swal({
                            icon: 'success',
                            title: '이용후기가 정상적으로 수정되었습니다.',
                            confirmButtonText: '확인',
                        }).then(() => {
                            router.push({name: 'user.review'});
                            
                        });
                        
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

    //작성한 이용후기 수정하기 - 관리자
    const editReviewAdmin = async (id) => {
        const content = document.getElementById('content').value; 
        const user_id = document.getElementById('user_id').value;
        const auction_id = document.getElementById('auction_id').value;
        const dealer_id = document.getElementById('dealer_id').value;
        const star = document.getElementById('reviewStarValue').value;

        //console.log(JSON.stringify(reviewForm));

        if(auction_id == "" || dealer_id == ""){
            alert("오류가 발생하였습니다. 관리자에게 문의해주세요.");
            return;
        }
        if(starScore == 0){ //별점을 수정(클릭)하지 않은 경우
            starScore = star;
        }

        setReviewValue(user_id, auction_id, dealer_id, starScore, content);
        //console.log(JSON.stringify(reviewForm));

        swal({
            title: '정말 수정하시겠어요?',
            text: '',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '수정',
            confirmButtonColor: '#ef4444',
            cancelButtonText: '취소',
            timer: 20000,
            timerProgressBar: true,
            reverseButtons: true
        })
        .then(result => {
            if (result.isConfirmed) {
                axios.put(`/api/reviews/${id}`, reviewForm)
                    .then(response => {
                        swal({
                            icon: 'success',
                            title: '이용후기가 정상적으로 수정되었습니다.',
                        }).then(() => {
                            location.href = "/admin/review";
                        });
                        
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

    
   
    // 작성한 이용후기 불러오기 (사용자별 불러오기)
    const getUserReview = async (id , page=1) => {
        return callApi({
            _type: 'get',
            _url:`/api/reviews`,
            _param: {
                _where:[`reviews.user_id:${id}`],
                _with:['auction','dealer'],
                _page:`${page}`
            }
        }).then(async result => {
            reviewsData.value = result.data;
            reviewPagination.value = result.rawData.data.meta;
            //return result.data;
        });

    }

    //작성한 이용후기별 불러오기
    const getUserReviewInfo = async (id) => {
        return callApi({
            _type: 'get',
            _url:`/api/reviews/${id}`,
            _param: {
                _with:['auction','dealer']
            }
        }).then(async result => {
            return result.data;
        });
    }

    // 작성한 이용후기 불러오기 (전체 리뷰 불러오기)
    const getAllReview = async (page = 1) => {
        return callApi({
            _type: 'get',
            _url:`/api/reviews`,
            _param: {
                _with:['auction']
            }
        }).then(async result => {
            //console.log(result);
            return result.data;
        });
    }

    // 홈 화면 리뷰 불러오기 (auction 정보 포함하지 않음.)
    const getHomeReview = async (page = 1) => {
        try {      
            const response = await axios.get(`/api/reviews?page=${page}&with=dealer`);
            reviewPagination.value = response.data.meta;
            //console.log('Pagination:', pagination.value);
            //console.log(response.data.data);
            reviewsData.value = response.data.data;

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
        if(date == null){
            return " ";
        } else{
            return date.split(' ')[0];
        }

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
        reviewPagination,
        submitReview,
        getUserReview,
        getAllReview,
        deleteReviewApi,
        editReview,
        editReviewAdmin,
        getUserReviewInfo,
        formattedAmount,
        splitDate,
        reviewsData,
        getHomeReview,
        getCarInfo,
        setInitialStarRating,
    }

}