export function cmmn() {
    const numberToKoreanUnit = (input) =>  {
        const units = ["", "만", "억", "조", "경"];
        input = parseInt(input.replace(/[^0-9]/g, ""));
        if (isNaN(input)) return "";
        
        let result = '';
        let unitIndex = 0;
        
        while (input > 0) {
            let part = input % 10000;
            if (part > 0) {
            result = part + (units[unitIndex] ? " " + units[unitIndex] : "") + " " + result;
            }
            input = parseInt(input / 10000);
            unitIndex++;
        }
        
        return result.trim();
      
    }

    const amtComma = (amt) => {
        return amt.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")+" 만원";
    }
    
    /**
     * 주소 검색
     *  <input v-model="auction.addr_post" class="form-control" type="hidden" id="addr_post">
        <input v-model="auction.addr1" class="form-control" id="addr1" @click="editPostCode('daumPostcodeInput')">
     *  <div id="daumPostcodeInput" style="display: none;">
            <img src="//t1.daumcdn.net/postcode/resource/images/close.png" @click="closePostcode('daumPostcodeInput')">
        </div>
     * function editPostCode(elementName) {
        openPostcode(elementName)
            .then(({ zonecode, address }) => {
            auction.addr_post = zonecode;
            auction.addr1 = address;
        })
}
     */
    function openPostcode(elementName) {
        console.log(elementName);
        const element = document.getElementById(elementName);

        return new Promise((resolve) => {
            const loadAndShowPostcode = () => {
                element.style.display = 'block';
    
                const postCode = new daum.Postcode({
                    oncomplete: data => resolve({ zonecode: data.zonecode, address: data.address }),
                    onclose: state => {
                        if (state === 'COMPLETE_CLOSE') {
                            element.style.display = 'none';
                        }
                    }
                });
    
                postCode.embed(element);
            };
    
            if (!window.daum || !window.daum.Postcode) {
                const script = document.createElement('script');
                script.src = "//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js";
                script.onload = () => {
                    console.log('Daum Postcode script loaded');
                    loadAndShowPostcode();
                };
                document.head.appendChild(script);
            } else {
                loadAndShowPostcode();
            }
        });
    }
    
    //창 닫기 동작
    function closePostcode(elementName) {
        const element = document.getElementById(elementName);
        element.style.display = 'none';
    }

    //callApi
    /*
        
        import { cmmn } from '@/hooks/cmmn';
        const { callApi } = cmmn();

        return callApi({
            _type: 'get',
            _url:`/api/reviews/${id}`,
            _param: ['auctions','reviews'],
            doesnthave: ['reviews'] -> with한 데이터 null 체크
        }).then(async result => {
            console.log(result);
            return result.data;
        });

        
    */  
     
    function parseApiCompleted(rstData, response) {
        console.log('cmmn.callApiGet result : ',response);
        rstData.rawData = response;
        if(response && response.data) {
            if(response.data.data) {
                rstData.data = response.data.data;
            } else {
                rstData.isError = true;
            }
            if(response.data.status == 'ok') {
                rstData.isSuccess = true;
            } else {
                rstData.isSuccess = false;
            }
        } else {
            rstData.isError = true;
            rstData.msg = 'unknown error';
        }
        return rstData;
    }
    function parseApiError(rstData, error) {
        console.log('cmmn.callApiGet result(error) : ',error);
        rstData.isError = true;
        rstData.rawData = error;
        if(error) {
            if (error.response) {
                rstData.status = error.response.status;
                if(error.response.data) {
                    //code :
                    if (error.response.data.status === 'fail') {
                        rstData.msg = error.response.data.message;
                        if(error.response.statusText != 'Bad Request') {
                            rstData.isAlert = true;
                        }
                    } else {
                        rstData.msg = error.response.data.errors;
                    }
                } else {
                    rstData.status = 5033;
                    rstData.msg = error.message;
                }
            } else {
                rstData.status = 5032;
                rstData.msg = error.message;
            }
        } else { //
            rstData.status = 5031;
            rstData.msg = 'unknown error';
        }
        return rstData;
    }
    const callApi = async(input) => {
        let rstData = {
            isError : false,
            isSuccess : false,
            isAlert : false,
            data : null,
            msg : '',
            status : 200,
            rawData : null,
        };
        let callType = '';
        let isPost = false;
        let isGet = false;
        let isPut = false;
        let urlPath = '';
        let urlParams = '';
        if(input) {
            if(input._url) {
                urlPath = input._url;
            } else {
                rstData.isError = true;
                rstData.msg = 'not exist _url';
                return rstData;
            }
            if(input._type) {
                callType = input._type;
                if(input._type.toUpperCase() == 'GET') {
                    isGet = true;
                } else if(input._type.toUpperCase() == 'POST') {
                    isPost = true;
                } else if(input._type.toUpperCase() == 'PUT') {
                    isPut = true;
                }
            } else {
                rstData.isError = true;
                rstData.msg = 'not exist _type';
                return rstData;
            }
            if(input._param) {
                if(isGet) {
                    if(input._param._where) {
                        let urlParamsWhere = '';
                        input._param._where.forEach(function(item){
                            if(urlParamsWhere) urlParamsWhere += '|';
                            urlParamsWhere += item;
                        });
                        if(urlParams) urlParams += '&';
                        urlParams += 'where='+urlParamsWhere;
                    }
                    if(input._param._with) {
                        let urlParamsWith = '';
                        input._param._with.forEach(function(item){
                            if(urlParamsWith) urlParamsWith += ',';
                            urlParamsWith += item;
                        });
                        if(urlParams) urlParams += '&';
                        urlParams += 'with='+urlParamsWith;
                    }
                    if(input._param._page) {
                        if(urlParams) urlParams += '&';
                        urlParams += 'page='+input._param._page;    
                    }
                    if(input._param._doesnthave){
                        let urlParamsWith = '';
                        input._param._with.forEach(function(item){
                            if(urlParamsWith) urlParamsWith += ',';
                            urlParamsWith += item;
                        });
                        if(urlParams) urlParams += '&';
                        urlParams += 'doesnthave='+urlParamsWith;
                    }
                    
                } else if(isPost) {
                    urlParams = input._param;
                } else if(isPut) {
                    urlParams = input._param;
                }
            } else {
                if(isPost || isPut) {
                    rstData.isError = true;
                    rstData.msg = 'not exist _param';
                    return rstData;
                }
            }       
        }
        console.log('cmmn.callApi 호출');       
        console.log('cmmn.callApi url : ' + urlPath);
        console.log('cmmn.callApi params : ' + urlParams);
        console.log('cmmn.callApi type : ' + callType);
        let returnData;

        if(isGet) {
            const response = await axios.get(`${urlPath}?${urlParams}`).then(response => {        
                returnData = parseApiCompleted(rstData,response);                
            })
            .catch(error => {            
                returnData = parseApiError(rstData,error);
            });
        } else if(isPost) {
            const response = await axios.post(`${urlPath}`,urlParams).then(response => {            
                returnData = parseApiCompleted(rstData,response);
            })
            .catch(error => {            
                returnData = parseApiError(rstData,error);
            });
        } else if(isPut) {
            const response = await axios.put(`${urlPath}`,urlParams).then(response => {            
                returnData = parseApiCompleted(rstData,response);
            })
            .catch(error => {            
                returnData = parseApiError(rstData,error);
            });
        }
        return returnData;
    }
    //End of callApi
    
    function splitDate (date){
        return date.split(' ')[0];
    }

    // param : yyyy-mm-dd
    // 날짜 => 요일
    function getDayOfWeek(dateString) {
        const date = new Date(dateString);

        const daysOfWeek = ["일", "월", "화", "수", "목", "금", "토"];
        
        const dayIndex = date.getDay();
        
        return daysOfWeek[dayIndex];
    }

    function formatDateAndTime(dateTimeString) {
        const dateObj = new Date(dateTimeString);

        const monthNames = ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'];
        
        const year = dateObj.getFullYear();
        const month = monthNames[dateObj.getMonth()];
        const date = dateObj.getDate();
        let hours = dateObj.getHours();
        const minutes = ('0' + dateObj.getMinutes()).slice(-2); // Ensure two digits for minutes
        
        const ampm = hours >= 12 ? '오후' : '오전';
        
        if (hours > 12) {
            hours -= 12;
        } else if (hours === 0) {
            hours = 12;
        }

        const formattedDateTime = `${year}년 ${month} ${date}일 ${ampm} ${hours}시 ${minutes}분`;
        
        return formattedDateTime;
    }

  // public swal
    /**
        salert({
            _swal: swal, //필수 지정
            //_type: 'C', //C:confirm , T:toast , A:alert
            _msg: '<b style="color:red">msg1</b>',
            //_title: '<b style="color:red">title1</b>',
            //_isHtml: false, //_msg가 HTML 태그 인 경우 활성화
            //_icon: 'E|W|I|Q', //E:error , W:warning , I:info , Q:question
            //_isClose: true,// 닫기 버튼 활성화
            //_btnOkLabel: '', //확인 버튼 라벨 변경시
            //_btnCancelLabel: '', //취소 버튼 라벨 변경시
            //_btnBatch: 'L|R', //확인 버튼 위치 지정
            //_timer: 10, //자동 닫기 타이머 설정
            //_isBackCancel:true, //창 외부 클릭 닫기 활성화
            //_isReturnFunction:false, //결과 함수 리턴 비활성화 , 기본 선언 필요
            //_addClassNm:'', // 클래스명 변경시 기입, 기본 클래스명 : wica-salert
            //_addOption: { //swal 기타 옵션 추가
            //    width: 700,
            //    padding: 150,
            //    background: '#fff url(https://image.shutterstock.com/z/stock-vector--exclamation-mark-exclamation-mark-hazard-warning-symbol-flat-design-style-vector-eps-444778462.jpg)',
            //    imageUrl: 'https://image.shutterstock.com/z/stock-vector--exclamation-mark-exclamation-mark-hazard-warning-symbol-flat-design-style-vector-eps-444778462.jpg',
            //    imageWidth: 200,
            //    imageHeight: 200,
            //    imageAlt: 'Custom image',
            //},
        },function(result){
            console.log('salert', result);
        }); 

        // Toast
        salert({
            _swal: swal, //필수 지정
            _type: 'T', //C:confirm , T:toast , A:alert
            _msg: '<b style="color:red">msg1</b>',
            //_isHtml: false, //_msg가 HTML 태그 인 경우 활성화
            _isBackCancel:true, //창 외부 클릭 닫기 활성화
            _isReturnFunction:false, //결과 함수 리턴 비활성화 , 기본 선언 필요
        }); 
     */
    const salert = (input,fnCallback) => {
        let rstData = {
            isError : false,
            isOk : false,
            msg : '',
            rawData : input,
            css: null,
        };
        let isReturn = true;
        if(input) {
            let _classNm = 'wica-salert';
            let _isBackCancel;
            let isRight;
            let _title;
            let _icon = null;//success , error , warning , info , question
            let _btnOkLabel = '확인';
            let _btnCancelLabel = '취소';
            let isConfirm;
            let isAlert;
            let isToast;
            let isClose;
            if(input._icon) {
                if(input._icon.toUpperCase() == 'E') {
                    _icon = 'error'
                } else if(input._icon.toUpperCase() == 'W') {
                    _icon = 'warning'
                } else if(input._icon.toUpperCase() == 'I') {
                    _icon = 'info'
                } else if(input._icon.toUpperCase() == 'Q') {
                    _icon = 'question'
                }
            }
            if(input._type) {
                if(input._type.toUpperCase() == 'C') {
                    isConfirm = true;
                } else if(input._type.toUpperCase() == 'A') {
                    isAlert = true;
                } else if(input._type.toUpperCase() == 'T') {
                    isToast = true;
                } else {
                    isToast = true;
                }
            } else {
                isToast = true;
            }
            if(input._title) {
                _title = input._title;
            }
            if(input._btnBatch) {
                if(input._btnBatch.toUpperCase() == 'R') { 
                    isRight = true;
                } else {
                    isRight = false;
                }
            }
            if(input._btnOkLabel) {
                _btnOkLabel = input._btnOkLabel;
            }
            if(input._btnCancelLabel) {
                _btnCancelLabel = input._btnCancelLabel; 
            }
            if(input._isBackCancel) {
                _isBackCancel = input._isBackCancel;
            }   
            if(input._addClassNm) {
                _classNm = input._addClassNm;
            }   
            if(input._isClose)      {
                isClose = input._isClose;
            }
            if(input._isReturnFunction != undefined) {
                isReturn = input._isReturnFunction;
            }
            let _customClass = {
                container: _classNm+'-container',
                confirmButton: _classNm+'-confirmButton',
                cancelButton: _classNm+'-cancelButton',
                popup: _classNm+'-popup',
                header: _classNm+'-header',
                title: _classNm+'-title',
                content: _classNm+'-content',
                icon: _classNm+'-icon',
                closeButton: _classNm+'-closeButton',
                image: _classNm+'-image',
                input: _classNm+'-input',
                actions: _classNm+'-actions',
                footer: _classNm+'-footer',
            };
            rstData.css = _customClass;
            let opt = {
                title: _title,
                icon: _icon,
                showConfirmButton: !isToast,
                showCancelButton: isConfirm,
                showCloseButton: isClose,
                confirmButtonText: _btnOkLabel,
                //confirmButtonColor: '#ef4444',
                cancelButtonText: _btnCancelLabel,
                //cancelButtonColor: '#ef4444', 
                reverseButtons: isRight,
                allowOutsideClick: _isBackCancel,
                customClass: _customClass,
            }; 
            if(input._addOption) {
                Object.assign(opt, input._addOption);
            }
            if(input._msg) {
                if(input._isHtml) {
                    opt.html = input._msg;
                } else {
                    opt.text = input._msg;
                }
            }
            if(input._timer) {
                opt.timer = 1000 * input._timer;
                opt.timerProgressBar = true;
            } else {
                if(isToast) {
                    opt.timer = 1000 * 2;
                    opt.timerProgressBar = false;
                }
            }
            input._swal(opt)
                .then(result => {
                    console.log(result);
                    if (result.isConfirmed) {
                        rstData.isOk = true;
                    }
                    if(isToast) {
                        rstData.isOk = true;
                    }
                    if(isReturn) fnCallback(rstData);
                })
        } else {
            rstData.isError = true;
            rstData.msg = 'not exist _param';
            if(isReturn) fnCallback(rstData);
        }        
    }
    //End of public swal

    //public wica swal
    /**
    
        wica.ntcn(swal)
        .param({}) // 리턴값에 전달 할 데이터
        .title('') // 알림 제목
        .useHtmlText() // HTML 태그 인 경우 활성화
        .icon('E') //E:error , W:warning , I:info , Q:question
        .useClose() // 닫기 버튼 활성화
        .useBackCancel() // 창 외부 클릭 닫기 활성화
        .labelOk('') //확인 버튼 라벨 변경시
        .labelCancel('') //취소 버튼 라벨 변경시
        .btnBatch('L') //확인 버튼 위치 지정 , L 기본
        .timer(10) //자동 닫기 타이머 설정 , toast 는 미지정시 2초 자동 처리
        .addClassNm('') // 클래스명 변경시 기입, 기본 클래스명 : wica-salert
        .addOption({}) //swal 기타 옵션 추가
        .callback(function(result) {

        })
        .toast('<b style="color:red">msg wica toast</b>');
        .alert('<b style="color:red">msg wica alert</b>');
        .confirm('<b style="color:red">msg wica confirm</b>');

        //
        wica.ntcn(swal).toast('이용후기가 정상적으로 삭제되었습니다.');
        //
        wica.ntcn(swal)
        .icon('I') //E:error , W:warning , I:info , Q:question
        .callback(function(result) {
            if(result.isOk){                                
                getAllReview(1);                                
            }
        })
        .alert('이용후기가 정상적으로 삭제되었습니다.');
        //
        wica.ntcn(swal)
        .title('오류가 발생하였습니다.')
        .icon('E') //E:error , W:warning , I:info , Q:question
        .alert('관리자에게 문의해주세요.');

        //참고
        wica.ntcn(swal).useHtmlText().alert('<b style="color:red">message !!</b>');

        wica.ntcn(swal).title('message !!!').alert();

        wica.ntcn(swal).icon('E').title('error message !!!').alert();

        wica.ntcn(swal).icon('E').title('error message !!!').callback(function(result){
            console.log(result);
        }).alert();

     */
    const wica = {
        _swal : null,
        _input : null,
        _isReturn: false,
        _rstData: null,
        _callback: null,
        toast : function(txt) {
            let _this = this;            
            _this = this.init(_this, txt, true, false);            
            _this._swal(_this._input.opt)
            .then(result => {       
                _this._rstData.isOk = true;
                if(_this._isReturn) _this._callback(_this._rstData);
            })            
        },
        alert : function(txt) {
            let _this = this;            
            _this = this.init(_this, txt, false, false);            
            _this._swal(_this._input.opt)
            .then(result => {       
                _this._rstData.isOk = true;
                if(_this._isReturn) _this._callback(_this._rstData);
            })            
        },
        confirm : function(txt) {
            let _this = this;            
            _this = this.init(_this, txt, false, true);            
            _this._swal(_this._input.opt)
            .then(result => {       
                if (result.isConfirmed) {
                    _this._rstData.isOk = true;
                }
                if(_this._isReturn) _this._callback(_this._rstData);
            })            
        },
        init : function(_this, txt, isToast, isConfirm) {
            let input = _this._input;
            let rstData = _this._rstData;
            if(input._isParam) {
                rstData.rawData = input.param;
            }
            let _classNm = 'wica-salert';
            if(input._isAddClassNm) {
                _classNm = input.addClassNm;
            }

            let _customClass = {
                container: _classNm+'-container',
                confirmButton: _classNm+'-confirmButton',
                cancelButton: _classNm+'-cancelButton',
                popup: _classNm+'-popup',
                header: _classNm+'-header',
                title: _classNm+'-title',
                content: _classNm+'-content',
                icon: _classNm+'-icon',
                closeButton: _classNm+'-closeButton',
                image: _classNm+'-image',
                input: _classNm+'-input',
                actions: _classNm+'-actions',
                footer: _classNm+'-footer',
            };
            rstData.css = _customClass;
            input.opt = {
                title: input._isTitle?input.title:null,
                icon: input._icon,
                showConfirmButton: !isToast,
                showCancelButton: isConfirm,
                showCloseButton: input._isUseClose,
                confirmButtonText: input._btnOkLabel,
                //confirmButtonColor: '#ef4444',
                cancelButtonText: input._btnCancelLabel,
                //cancelButtonColor: '#ef4444', 
                reverseButtons: input._isRight,
                allowOutsideClick: input._isUseBackCancel,
                customClass: _customClass,
            }; 
            if(input._isAddOption) {
                Object.assign(input.opt, input.addOption);
            }
            if(txt) {
                if(input._isHtml) {
                    input.opt.html = txt;
                } else {
                    input.opt.text = txt;
                }
            }            
            if(input._isTimer) {
                input.opt.timer = 1000 * input.timer;
                input.opt.timerProgressBar = true;
            } else {
                if(isToast) {
                    input.opt.timer = 1000 * 2;
                    input.opt.timerProgressBar = false;
                }
            }
            return _this;
        },
        ntcn : function(_swal) {
            let newObj = Object.create(this);
            newObj._swal = _swal;
            newObj._rstData = {
                isError : false,
                isOk : false,
                msg : '',
                rawData : null,
                css: null,
            };
            newObj._isReturn = false;
            newObj._input = {
                _isHtml : false,
                _btnOkLabel : '확인',
                _btnCancelLabel : '취소',
                _isRight : false,
                _isTimer : false,
                _isAddOption : false,
                _isAddClassNm : false,
                _isUseClose : false,
                _isUseBackCancel : false,
                _isParam : false,
            }                
            return newObj;
        },
        param : function(input) {
            let _this = this;
            _this._input.param = input;
            if(input) {
                _this._input._isParam = true;
            }
            return _this;
        },
        title : function(input) {
            let _this = this;
            _this._input.title = input;
            if(input) {
                _this._input._isTitle = true;
            } else {
                _this._input._isTitle = false;
            }
            return _this;
        },
        useHtmlText : function() {
            let _this = this;
            _this._input._isHtml = true;
            return _this;
        },
        icon : function(input) {
            let _this = this;
            _this._input.icon = input;
            if(input) {
                if(input.toUpperCase() == 'E') {
                    _this._input._icon = 'error'
                } else if(input.toUpperCase() == 'W') {
                    _this._input._icon = 'warning'
                } else if(input.toUpperCase() == 'I') {
                    _this._input._icon = 'info'
                } else if(input.toUpperCase() == 'Q') {
                    _this._input._icon = 'question'
                } else {
                    _this._input._icon = null;
                }
            }
            return _this;
        },
        useClose : function() {
            let _this = this;
            _this._input._isUseClose = true;
            return _this;
        },
        useBackCancel : function() {
            let _this = this;
            _this._input._isUseBackCancel = true;
            return _this;
        },
        labelOk : function(input) {
            let _this = this;
            _this._input.labelOk = input;
            if(input) {
                _this._input._btnOkLabel = input;
            }
            return _this;
        },
        labelCancel : function(input) {
            let _this = this;
            _this._input.labelCancel = input;
            if(input) {
                _this._input._btnCancelLabel = input;
            }
            return _this;
        },
        btnBatch : function(input) {
            let _this = this;
            _this._input.btnBatch = input;
            if(input) {
                if(input.toUpperCase() == 'R') { 
                    _this._input._isRight = true;
                }
            }
            return _this;
        },
        timer : function(input) {
            let _this = this;
            _this._input.timer = input;
            if(input) {
                _this._input._isTimer = true;
            }
            return _this;
        },
        addClassNm : function(input) {
            let _this = this;
            _this._input.addClassNm = input;
            if(input) {
                _this._input._isAddClassNm = true;
            }
            return _this;
        },
        addOption : function(input) {
            let _this = this;
            _this._input.addOption = input;
            if(input) {
                _this._input._isAddOption = true;
            }
            return _this;
        },
        callback : function(input) {
            let _this = this;
            _this._callback = input;
            if(input) {
                _this._isReturn = true;
            }
            return _this;
        }        
    }
    //End of public wica swal

    return {
      numberToKoreanUnit,
      amtComma,
      openPostcode,
      closePostcode,
      callApi,
      splitDate,
      getDayOfWeek,
      formatDateAndTime,
      salert,
      wica,
    }
  }
