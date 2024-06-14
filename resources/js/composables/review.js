import axios from 'axios';
import { reactive , ref , inject } from 'vue';
import useAuctions from "./auctions";
import { useRouter } from 'vue-router';
import { cmmn } from '@/hooks/cmmn';

let starScore = 0;

export function initReviewSystem() {
    const { callApi , salert } = cmmn();
    const router = useRouter();


    const swal = inject('$swal');
    const { getAuctionById, AuctionCarInfo } = useAuctions();
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

    // 리뷰 작성 post - 사용자
    const submitReview = async (review) => {
        review.star = starScore;

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
        salert({
            _swal: swal, //필수 지정
            _title: '작성하시겠습니까?',
            _type: 'C',
            _isHtml: true, 
            _icon: 'Q',
        },function(result){
            if(result.isOk){
                axios.post(`/api/reviews`, form)
                    .then(response => {
                        salert({
                            _type: 'A',
                            _swal: swal, //필수 지정
                            _msg: '이용후기가 정상적으로 작성되었습니다.',
                            _icon: 'I',
                        },function(result){
                            if(result.isOk){
                                router.push({name: 'user.review'});     
                            }
                        });
                    })
                    .catch(error => {
                        salert({
                            _type: 'A',
                            _swal: swal, //필수 지정
                            _title: '오류가 발생하였습니다.',
                            _msg: '관리자에게 문의해주세요.',
                            _icon: 'E',
    
                        },function(result){
                            console.log(result);
                        })
                    })
            }
            //console.log('salert', result);
        });
    }
    
    // 작성한 이용후기 삭제하기 - 관리자
    const adminDeleteReview = async (id) => {
        salert({
            _swal: swal, //필수 지정
            _title: '삭제하시겠습니까?',
            _msg: '삭제된 정보는 복구할 수 없습니다.',
            _type: 'C',
            _isHtml: true, 
            _icon: 'W',
        },function(result){
            if(result.isOk){
                axios.delete(`/api/reviews/${id}`)
                    .then(response => {
                        salert({
                            _type: 'A',
                            _swal: swal, //필수 지정
                            _msg: '이용후기가 정상적으로 삭제되었습니다.',
                            _icon: 'I',
                        },function(result){
                            console.log("이용후기");
                            if(result.isOk){                                
                                getAllReview(1);                                
                            }
                        });
                    })
                    .catch(error => {
                        salert({
                            _type: 'A',
                            _swal: swal, //필수 지정
                            _title: '오류가 발생하였습니다.',
                            _msg: '관리자에게 문의해주세요.',
                            _icon: 'E',

                        },function(result){
                            console.log(result);
                        })
                    })
            }
            //console.log('salert', result);
        }); 
        

    }
     
    // 작성한 이용후기 삭제하기 - 사용자
    const userDeleteReview = async (id , userId) => {
        console.log(userId);
        salert({
            _swal: swal, //필수 지정
            _title: '삭제하시겠습니까?',
            _msg: '삭제된 정보는 복구할 수 없습니다.',
            _type: 'C',
            _isHtml: true, 
            _icon: 'W',
        },function(result){
            if(result.isOk){
                axios.delete(`/api/reviews/${id}`)
                    .then(response => {
                        salert({
                            _type: 'A',
                            _swal: swal, //필수 지정
                            _msg: '이용후기가 정상적으로 삭제되었습니다.',
                            _icon: 'I',
                        },function(result){
                            console.log("이용후기");
                            if(result.isOk){                                
                                getUserReview(userId, 1);                                
                            }
                        });
                    })
                    .catch(error => {
                        salert({
                            _type: 'A',
                            _swal: swal, //필수 지정
                            _title: '오류가 발생하였습니다.',
                            _msg: '관리자에게 문의해주세요.',
                            _icon: 'E',

                        },function(result){
                            console.log(result);
                        })
                    })
            }
            //console.log('salert', result);
        }); 
        

    }

    //작성한 이용후기 수정하기 - 사용자, 관리자 공통
    const editReview = async (id, review, role) => {

        if(starScore > 0){
            review.star = starScore;
        }
   
        const form = {
            review
        }

        if(review.auction_id == "" || review.dealer_id == "" || review.user_id == ""){
            alert("오류가 발생하였습니다. 관리자에게 문의해주세요.");
            return;
        }

        salert({
            _swal: swal, //필수 지정
            _title: '수정하시겠습니까?',
            _type: 'C',
            _isHtml: true, 
            _icon: 'Q',
        },function(result){
            if(result.isOk){
                axios.put(`/api/reviews/${id}`, form)
                    .then(response => {
                        salert({
                            _type: 'A',
                            _swal: swal, //필수 지정
                            _msg: '이용후기가 정상적으로 수정되었습니다.',
                            _icon: 'I',
                            _isHtml: true, //_msg가 HTML 태그 인 경우 활성화
                        },function(result){
                            if(result.isOk){
                                if(role === 'user'){
                                    router.push({name: 'user.review'});
                                } else if(role === 'admin'){
                                    router.push({name: 'review.index'});
                                }
                                
                                    
                            }
                        });
                    })
                    .catch(error => {
                        salert({
                            _type: 'A',
                            _swal: swal, //필수 지정
                            _title: '오류가 발생하였습니다.',
                            _msg: '관리자에게 문의해주세요.',
                            _icon: 'E',
                            _isHtml: true, //_msg가 HTML 태그 인 경우 활성화
    
                        },function(result){
                            console.log(result);
                        })
                    })
            }
            //console.log('salert', result);
        });
        
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
                _with:['auction'],
                _page:`${page}`
            }
        }).then(async result => {
            reviewsData.value = result.data;
            console.log(reviewsData.value);
            reviewPagination.value = result.rawData.data.meta;
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
        reviewPagination,
        submitReview,
        getUserReview,
        getAllReview,
        adminDeleteReview,
        userDeleteReview,
        editReview,
        getUserReviewInfo,
        formattedAmount,
        splitDate,
        reviewsData,
        getHomeReview,
        getCarInfo,
        setInitialStarRating,
    }

}