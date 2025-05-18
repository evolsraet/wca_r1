import { reactive , ref , inject } from 'vue';
import useAuctions from "./auctions";
import { useRouter } from 'vue-router';
import { cmmn } from '@/hooks/cmmn';

let starScore = 0;

export function initReviewSystem() {
    const { wicac , wica } = cmmn();
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
    let scoreDisplay = document.querySelector('.rating-score'); // 점수를 표시할 span 요소
    checkedRate();


    rateWrap.forEach(wrap => {
        
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
                    des.textContent = '별로에요';
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
            swal.fire({
                title: '오류가 발생하였습니다. 관리자에게 문의해주세요.',
                icon: 'error',
                confirmButtonText: '확인',
            });
            return;
        }
        if(starScore == 0 && starScore == ""){
            // alert("별점을 입력해주세요.");

            swal.fire({
                title: '별점을 입력해주세요.',
                icon: 'error',
                confirmButtonText: '확인',
            });

            return;
        }
        const form = {
            review
        }
        console.log(JSON.stringify(form));

        wica.ntcn(swal)
        .title('작성하시겠습니까?') // 알림 제목
        .icon('Q') //E:error , W:warning , I:info , Q:question
        .addClassNm('cmm-review-custom') // 클래스명 변경시 기입, 기본 클래스명 : wica-salert
        .callback(function(result) {
            if(result.isOk){
                //console.log(result);
                wicac.conn()
                .url(`/api/reviews`) //호출 URL
                .param(form)
                .callback(function(result) {
                    if(result.isError){
                        wica.ntcn(swal)
                        .title('오류가 발생하였습니다.')
                        .icon('E') //E:error , W:warning , I:info , Q:question
                        .alert('관리자에게 문의해주세요.');
                    }else{
                        wica.ntcn(swal)
                        .addClassNm('cmm-review-custom') // 클래스명 변경시 기입, 기본 클래스명 : wica-salert
                        .icon('I') //E:error , W:warning , I:info , Q:question
                        .callback(function(result2) {
                            if(result2.isOk){                                
                                router.push({name: 'user.review'});                            
                            }
                        })
                        .alert('이용후기가 정상적으로 작성되었습니다.');
                    }
                })
                .post();
            }
        }).confirm();
    }
    
    // 작성한 이용후기 삭제하기 - 관리자
    const adminDeleteReview = async (id) => {
        wica.ntcn(swal)
        .param({ _id : id }) // 리턴값에 전달 할 데이터
        .title('삭제하시겠습니까?') // 알림 제목
        .icon('W') //E:error , W:warning , I:info , Q:question
        .callback(function(result) {
            if(result.isOk){
                wicac.conn()
                .url(`/api/reviews/${id}`)
                .callback(function(result2) {
                    if(result2.isSuccess){
                        wica.ntcn(swal)
                        .addClassNm('cmm-review-custom') // 클래스명 변경시 기입, 기본 클래스명 : wica-salert
                        .icon('I') //E:error , W:warning , I:info , Q:question
                        .callback(function(result3) {
                            if(result3.isOk){                                
                                getAllReview(1);                                
                            }
                        })
                        .alert('이용후기가 정상적으로 삭제되었습니다.');
                    }else{
                        wica.ntcn(swal)
                        .title('오류가 발생하였습니다.')
                        .icon('E') //E:error , W:warning , I:info , Q:question
                        .alert('관리자에게 문의해주세요.');
                    }
                })
                .delete();
            }
        })
        .confirm('삭제된 정보는 복구할 수 없습니다.');   
    }
     
    // 작성한 이용후기 삭제하기 - 사용자
    const userDeleteReview = async (id , userId) => {
        wica.ntcn(swal)
        .param({ _id : id }) // 리턴값에 전달 할 데이터
        .title('삭제하시겠습니까?') // 알림 제목
        .icon('W') //E:error , W:warning , I:info , Q:question
        .labelOk('삭제') //확인 버튼 라벨 변경시
        .labelCancel('취소') //취소 버튼 라벨 변경시
        .addClassNm('review-custom') // 클래스명 변경시 기입, 기본 클래스명 : wica-salert
        .callback(function(result) {
            if(result.isOk){
                console.log(result);
                wicac.conn()
                .url(`/api/reviews/${id}`)
                .callback(function(result2) {
                    if(result2.isSuccess){
                        wica.ntcn(swal)
                        .title('이용후기가 삭제되었습니다.')
                        .addClassNm('cmm-review-custom') // 클래스명 변경시 기입, 기본 클래스명 : wica-salert
                        .icon('I') //E:error , W:warning , I:info , Q:question
                        .addOption({ padding: 20})
                        .callback(function(result3) {
                            if(result3.isOk){                                
                                getUserReview(userId, 1);                                 
                            }
                        })
                        .alert('');
                    }else{
                        wica.ntcn(swal)
                        .title('오류가 발생하였습니다.')
                        .icon('E') //E:error , W:warning , I:info , Q:question
                        .alert('관리자에게 문의해주세요.');
                    }
                })
                .delete();
            }
        })
        .confirm('삭제된 정보는 복구할 수 없습니다.');   
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

        wica.ntcn(swal)
        .title('수정하시겠습니까?') // 알림 제목
        .icon('Q') //E:error , W:warning , I:info , Q:question
        .addClassNm('cmm-review-custom') // 클래스명 변경시 기입, 기본 클래스명 : wica-salert
        .callback(function(result) {
            if(result.isOk){
                //console.log(result);
                wicac.conn()
                .url(`/api/reviews/${id}`) //호출 URL
                .param(form)
                .callback(function(result2) {
                    if(result2.isSuccess){
                        wica.ntcn(swal)
                        .icon('I') //E:error , W:warning , I:info , Q:question
                        .addClassNm('cmm-review-custom') // 클래스명 변경시 기입, 기본 클래스명 : wica-salert
                        .callback(function(result3) {
                            if(result3.isOk){                                
                                if(role === 'user'){
                                    router.push({name: 'user.review'});
                                } else if(role === 'admin'){
                                    router.push({name: 'review.index'});
                                }                 
                            }
                        })
                        .alert('이용후기가 정상적으로 수정되었습니다.');
                    }else{
                        wica.ntcn(swal)
                        .title('오류가 발생하였습니다.')
                        .icon('E') //E:error , W:warning , I:info , Q:question
                        .alert('관리자에게 문의해주세요.');
                    }
                })
                .put();
            }
        }).confirm();
        
    }
   
    // 작성한 이용후기 불러오기 (사용자별 불러오기)
    const getUserReview = async (id , page=1) => {

        return wicac.conn()
        //.log() //로그 출력
        .url(`/api/reviews`) //호출 URL
        .where([
            `reviews.user_id:${id}`
        ]) 
        .with([
            'auction',
            'dealer'
        ]) 
        .page(`${page}`) //페이지 0 또는 주석 처리시 기능 안함
        .callback(function(result) {
            reviewsData.value = result.data;
            reviewPagination.value = result.rawData.data.meta;
            return result.data; //결과값을 후 처리 할때 필수 선언
        })
        .get();
    }

    //작성한 이용후기별 불러오기(로그인 전 리뷰는 auction 불러오지 않음.)
    const getUserReviewInfo = async (
        id,
        isHomeReview = false
    ) => {
        const apiList = ['dealer'];
        if(!isHomeReview){
            apiList.push('auction');
        }

        return wicac.conn()
        .url(`/api/reviews/${id}`)
        .with(apiList)
        .callback(function(result) {
            return result.data;
        })
        .get();
    }

    // 작성한 이용후기 전체 불러오기 (전체 리뷰 불러오기 , 로그인 전 리뷰는 auction 불러오지 않음.)
    const getAllReview = async (
            page = 1,
            isHomeReview = false,
            column = 'created_at',
            direction = 'desc',
            star = 'all',
            search_title = ''
        ) => {

        const apiList = ['dealer'];
        const whereList = [];
        if(!isHomeReview){
            apiList.push('auction');
            apiList.push('user');
        }
        if(star != 'all'){
            whereList.push(`reviews.star:${star}`)
        }
        return wicac.conn()
            .url(`/api/reviews`)
            .with(apiList)
            .where(whereList)
            .search(search_title)
            .page(`${page}`)
            .order([
                [`${column}`,`${direction}`]
            ])
            .callback(function(result) {
                console.log('wicac.conn callback ' , result.data);
                reviewsData.value = result.data;
                reviewPagination.value = result.rawData.data.meta;
            })
            .log()
            .get();
    }

    // 관리자페이지 - 대시보드 리뷰 개수 가져오기
    const getWriteReviewCnt = async() => {
        return wicac.conn()
        .url(`/api/reviews`)
        .callback(function(result) {
            return result.page.total;
        })
        .get();
    }
        
    // 홈 화면 리뷰 불러오기 (auction 정보 포함하지 않음.)
    const getHomeReview = async (page = 1) => {
        wicac.conn()
        .url(`/api/reviews`)
        .with([
            'dealer',
        ]) 
        .page(page) //불러올 페이지 번호( 0 또는 주석 처리시 기능 안함 )
        .callback(function(result) {
            console.log(result);
            if(result.isSuccess){
                reviewPagination.value = result.page;
                reviewsData.value = result.data;
            }else{
                console.log(error);
            }
        })
        .get();
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

    const getReviewsDeleteList = async (auctionId) => {

        const whereList = [];
        whereList.push(`reviews.auction_id:${auctionId}`)
     
        return wicac.conn()
        .url(`/api/reviews?where=reviews.auction_id:${auctionId}&withTrashed=''`)
        .log()
        .callback(function(result) {
            return result.data;
        })
        .get();
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
        getWriteReviewCnt,
        setInitialStarRating,
        getReviewsDeleteList,
    }

}