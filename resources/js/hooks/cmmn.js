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

    //만원 단위
    const amtComma = (amt) => {
        return amt.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")+" 만원";
    }
    
    //원 단위
    function formatCurrency(amount) {
        return amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',') + '원';
    }

    /**
     * 주소 검색
     *  <input v-model="auction.addr_post" class="form-control" type="hidden" id="addr_post">
        <input v-model="auction.addr1" class="form-control" id="addr1" @click="editPostCode('daumPostcodeInput')">
     *  <div id="daumPostcodeInput" style="display: none; border: 1px solid; width: 100%; height: 466px; margin: 5px 0px; position: relative">
            <img src="//t1.daumcdn.net/postcode/resource/images/close.png" style="cursor:pointer;position:absolute;right:0px;top:-1px;z-index:1" @click="closePostcode('daumPostcodeInput')">
        </div>
        //style 붙여야 깨지지 않음. 닫기 버튼 이미지 붙여야 함

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
                var currentScroll = Math.max(document.body.scrollTop, document.documentElement.scrollTop);
                const postCode = new daum.Postcode({
                    oncomplete: data => resolve({ zonecode: data.zonecode, address: data.address }),
                    onclose: state => {
                        if (state === 'COMPLETE_CLOSE') {
                            element.style.display = 'none';
                        }
                    },
                    onresize: function(size) {
                        element.style.height = size.height+'px';
                    },
                    width: '100%',
                    height: '100%'
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

    //public wica connector
    /***
     
        import { cmmn } from '@/hooks/cmmn';
        const { wicac } = cmmn();

    
        return wicac.conn() //결과값을 후 처리 할때 필수 선언

        wicac.conn()
        .log() //로그 출력 (파라미터 및 결과 등)
        .logDetail() //상세 로그 출력(기본 로그 포함하여 상세하게)
        .url(`/api/reviews/${id}`) //호출 URL
        .multipart() //첨부파일 있을 경우 선언
        .param({
            'id' : 1
        }) 
        .search('')
        .where([
            'w1=1',
            'w2=1',
        ]) 
        .with([
            'auction',
            'dealer',
        ]) 
        .doesnthave([
            'd1=1',
            'd2=2',
        ])
        .order([
            ['auction','asc'],
            ['auction','desc']
        ])
        .pageLimit(10) //한 페이지당 나올 게시물 갯수( 0 또는 주석 처리시 기능 안함 )
        .page(10) //불러올 페이지 번호( 0 또는 주석 처리시 기능 안함 )
        .callback(function(result) {
            console.log('wicac.conn callback ' , result);
            return result.data; //결과값을 후 처리 할때 필수 선언
        })
        .get();
        .post();
        .put();
        .delete();

     */
    const wicac = {
        _input : null,
        _callback : null,
        _isReturn : false,
        conn : function() {
            let newObj = Object.create(this);
            newObj._input = {
                _url : null,
                _param : null,
                _where : null,
                _with : null,
                _doesnthave : null,
                _order : null,
                _page : 1,
                _searchText : null,
                _isUrl : false,
                _isParam : false,
                _isWhere : false,
                _isWith : false,
                _isDoesnthave : false,
                _isOrder : false,
                _isPage : false,
                _isPageLimit : false,
                _isPost : false,
                _isGet : false,
                _isPut : false,
                _isDelete : false,
                _isMultipart : false,
                _isLog : false,
                _isLogDetail : false,
                _isSearchText : false,
                _rstData : {
                    isError : false,
                    isSuccess : false,
                    isAlert : false,
                    data : null,
                    msg : '',
                    status : 200,
                    rawData : null,
                    page : null,
                },
            };
            return newObj;
        },
        log : function() {
            let _this = this;
            _this._input._isLog = true;
            return _this;
        },
        logDetail : function() {
            let _this = this;
            _this._input._isLogDetail = true;
            return _this;
        },
        url : function(input) {
            let _this = this;
            if(_this._input._isLogDetail) console.log('cmmn wicac [ url(string) ] 함수 선언 ',input);
            _this._input._url = input;
            if(input) {                
                _this._input._isUrl = true;
            }
            return _this;
        },
        param : function(input) {
            let _this = this;
            if(_this._input._isLogDetail) console.log('cmmn wicac [ param(object) ] 함수 선언 ',input);
            _this._input._param = input;
            if(input) {
                _this._input._isParam = true;
            }
            return _this;
        },
        multipart : function() {
            let _this = this;
            if(_this._input._isLogDetail) console.log('cmmn wicac [ multipart() ] 함수 선언 ');
            _this._input._isMultipart = true;
            return _this;
        },
        where : function(input) {
            let _this = this;
            if(_this._input._isLogDetail) console.log('cmmn wicac [ where(array) ] 함수 선언 ',input);
            _this._input._where = input;
            if(input && input != null && input.length) {
                _this._input._isWhere = true;
            }
            return _this;
        },
        with : function(input) {
            let _this = this;
            if(_this._input._isLogDetail) console.log('cmmn wicac [ with(array) ] 함수 선언 ',input);
            _this._input._with = input;
            if(input) {
                _this._input._isWith = true;
            }
            return _this;
        },
        doesnthave : function(input) {
            let _this = this;
            if(_this._input._isLogDetail) console.log('cmmn wicac [ doesnthave(array) ] 함수 선언 ',input);
            _this._input._doesnthave = input;
            if(input) {
                _this._input._isDoesnthave = true;
            }
            return _this;
        },
        order : function(input) {
            let _this = this;
            if(_this._input._isLogDetail) console.log('cmmn wicac [ order(array) ] 함수 선언 ',input);
            _this._input._order = input;
            if(input) {
                _this._input._isOrder = true;
            }
            return _this;
        },
        page : function(input) {
            let _this = this;
            if(_this._input._isLogDetail) console.log('cmmn wicac [ page(int) ] 함수 선언 ',input);
            _this._input._page = input;
            if(input) {
                _this._input._isPage = true;
            }
            return _this;
        },     
        pageLimit : function(input) {
            let _this = this;
            if(_this._input._isLogDetail) console.log('cmmn wicac [ pageLimit(int) ] 함수 선언 ',input);
            _this._input._pageLimit = input;
            if(input) {
                _this._input._isPageLimit = true;
            }
            return _this;
        }, 
        search : function(input) {
            let _this = this;
            if(_this._input._isLogDetail) console.log('cmmn wicac [ search(string) ] 함수 선언 ',input);
            _this._input._searchText = input;
            if(input) {                
                _this._input._isSearchText = true;
            }
            return _this;
        },   
        get : function() {
            let _this = this;
            if(_this._input._isLogDetail) console.log('cmmn wicac [ get() ] 함수 선언 ');
            _this._input._isGet = true;
            return this.call().then(result => {
                if(_this._isReturn) {
                    return _this._callback(result);
                }
                return result;
            });
        },
        post : function() {
            let _this = this;
            if(_this._input._isLogDetail) console.log('cmmn wicac [ post() ] 함수 선언 ');
            _this._input._isPost = true;
            return this.call().then(result => {
                if(_this._isReturn) {
                    return _this._callback(result);
                }
                return result;
            });
        },
        put : function() {
            let _this = this;
            if(_this._input._isLogDetail) console.log('cmmn wicac [ put() ] 함수 선언 ');
            _this._input._isPut = true;
            return this.call().then(result => {
                if(_this._isReturn) {
                    return _this._callback(result);
                }
                return result;
            });
        },
        delete : function() {
            let _this = this;
            if(_this._input._isLogDetail) console.log('cmmn wicac [ delete() ] 함수 선언 ');
            _this._input._isDelete = true;
            return this.call().then(result => {
                if(_this._isReturn) {
                    return _this._callback(result);
                }
                return result;
            });
        },
        parseParam : function(_input,urlParams) {
            if(_input._isLogDetail) console.log('cmmn wicac [ parseParam ] 로직 시작 ');
            if(_input._isWhere) {
                let urlParamsWhere = '';
                _input._where.forEach(function(item){
                    if(urlParamsWhere) urlParamsWhere += '|';
                    urlParamsWhere += item;
                });
                if(urlParams) urlParams += '&';
                urlParams += 'where='+urlParamsWhere;
                if(_input._isLogDetail) console.log('cmmn wicac [ parseParam ] where 처리 ',_input._where,' , where='+urlParamsWhere);
            }
            if(_input._isWith) {
                let urlParamsWith = '';
                _input._with.forEach(function(item){
                    if(urlParamsWith) urlParamsWith += ',';
                    urlParamsWith += item;
                });
                if(urlParams) urlParams += '&';
                urlParams += 'with='+urlParamsWith;
                if(_input._isLogDetail) console.log('cmmn wicac [ parseParam ] with 처리 ',_input._with,' , with='+urlParamsWith);
            }
            if(_input._isSearchText) {
                if(urlParams) urlParams += '&';
                urlParams += 'search_text='+_input._searchText;    
                if(_input._isLogDetail) console.log('cmmn wicac [ parseParam ] search 처리 ',_input._searchText);
            }
            if(_input._isPage && _input._page > 0) {
                if(urlParams) urlParams += '&';
                urlParams += 'page='+_input._page;    
                if(_input._isLogDetail) console.log('cmmn wicac [ parseParam ] page 처리 ',_input._page);
            }
            if(_input._isPageLimit && _input.pageLimit > 0) {
                if(urlParams) urlParams += '&';
                urlParams += 'paginate='+_input._pageLimit;    
                if(_input._isLogDetail) console.log('cmmn wicac [ parseParam ] pageLimit 처리 ',_input._pageLimit);
            }
            if(_input._isDoesnthave){
                let urlParamsDoesnthave = '';
                _input._doesnthave.forEach(function(item){
                    if(urlParamsDoesnthave) urlParamsDoesnthave += ',';
                    urlParamsDoesnthave += item;
                });
                if(urlParams) urlParams += '&';
                urlParams += 'doesnthave='+urlParamsDoesnthave;
                if(_input._isLogDetail) console.log('cmmn wicac [ parseParam ] doesnthave 처리 ',_input._doesnthave,' , doesnthave='+urlParamsDoesnthave);
            }
            if(_input._isOrder){
                let urlParamsOrder1 = '';
                let urlParamsOrder2 = '';
                _input._order.forEach(function(item){
                    if(urlParamsOrder1) urlParamsOrder1 += ',';
                    if(urlParamsOrder2) urlParamsOrder2 += ',';
                    urlParamsOrder1 += item[0];
                    urlParamsOrder2 += item[1];
                });
                if(urlParams) urlParams += '&';
                urlParams += 'order_column='+urlParamsOrder1+'&order_direction='+urlParamsOrder2;
                if(_input._isLogDetail) console.log('cmmn wicac [ parseParam ] order 처리 ',_input._order,' , order_column='+urlParamsOrder1,' , order_direction='+urlParamsOrder2);
            }
            if(urlParams) urlParams = '?' + urlParams;
            if(_input._isLogDetail) console.log('cmmn wicac [ parseParam ] 로직 종료 ',urlParams);
            return urlParams;
        },
        call : async function() {
            let _this = this;
            let _input = _this._input;
            if(!_input._isUrl) {
                _input._rstData.isError = true;
                _input._rstData.msg = 'not exist _url';
                return _input._rstData;
            }
            if(_input._isGet) {
                let urlParams = '';
                if(_input._isParam) {
                    let urlParamsData = '';
                    Object.keys(_input._param).forEach(function(key) {
                        if(urlParamsData) urlParamsData += '&';
                        urlParamsData += key +'='+ _input._param[key];
                    });
                    urlParams += urlParamsData;
                }
                urlParams = this.parseParam(_input, urlParams);
                if(_input._isLog || _input._isLogDetail) console.log('cmmn wicac GET [ ' + _this._input._url+urlParams + ' ]');
                await axios.get(_this._input._url+urlParams).then(response => {      
                    _input._rstData = parseApiCompleted(_input,_input._rstData,response);                                 
                })
                .catch(error => {            
                    _input._rstData = parseApiError(_input,_input._rstData,error);                    
                });
                if(_input._isLog || _input._isLogDetail) console.log('cmmn wicac GET [ ' + _this._input._url+urlParams + ' ] result : ',_input._rstData);
                return _input._rstData;
            } else if(_input._isPost) {
                let urlParamsData = '';
                if(_input._isParam) {
                    urlParamsData = _input._param;
                }
                let urlParams = '';
                urlParams = this.parseParam(_input, urlParams);
                let urlHeaders = null;
                if(_input._isMultipart){
                    urlParamsData.append('_method', 'PUT');
                    urlHeaders = {
                        headers : {
                            'Content-Type': 'multipart/form-data'
                        }
                    };
                }
                if(_input._isLog || _input._isLogDetail) console.log('cmmn wicac POST'+(_input._isMultipart?'(Multipart)':'')+' [ ' + _this._input._url+urlParams +' ]', urlParamsData);
                await axios.post(_this._input._url+urlParams,urlParamsData,urlHeaders).then(response => {            
                    _input._rstData = parseApiCompleted(_input,_input._rstData,response);
                })
                .catch(error => {            
                    _input._rstData = parseApiError(_input,_input._rstData,error);
                });  
                if(_input._isLog || _input._isLogDetail) console.log('cmmn wicac POST '+(_input._isMultipart?'(Multipart)':'')+' [ ' + _this._input._url+urlParams +' ] result : ',_input._rstData);
                return _input._rstData;              
            } else if(_input._isPut) {
                let urlParamsData = '';
                if(_input._isParam) {
                    urlParamsData = _input._param;
                }
                let urlParams = '';
                urlParams = this.parseParam(_input, urlParams);
                let urlHeaders = null;
                if(_input._isMultipart){
                    urlHeaders = {
                        headers : {
                            'Content-Type': 'multipart/form-data'
                        }
                    };
                }
                if(_input._isLog || _input._isLogDetail) console.log('cmmn wicac PUT'+(_input._isMultipart?'(Multipart)':'')+' [ ' + _this._input._url+urlParams +' ]', urlParamsData);
                await axios.put(_this._input._url+urlParams,urlParamsData).then(response => {            
                    _input._rstData = parseApiCompleted(_input,_input._rstData,response);
                })
                .catch(error => {            
                    _input._rstData = parseApiError(_input,_input._rstData,error);
                });
                if(_input._isLog || _input._isLogDetail) console.log('cmmn wicac PUT '+(_input._isMultipart?'(Multipart)':'')+' [ ' + _this._input._url+urlParams +' ] result : ',_input._rstData);
                return _input._rstData;
            } else if(_input._isDelete) {
                let urlParamsData = '';
                if(_input._isParam) {
                    urlParamsData = _input._param;
                }
                urlParams = this.parseParam(_input, urlParams);
                if(_input._isLog || _input._isLogDetail) console.log('cmmn wicac DELETE [ ' + _this._input._url+urlParams +' ]', urlParamsData);
                await axios.delete(_this._input._url+urlParams,urlParamsData).then(response => {            
                    _input._rstData = parseApiCompleted(_input,_input._rstData,response);
                })
                .catch(error => {            
                    _input._rstData = parseApiError(_input,_input._rstData,error);
                });
                if(_input._isLog || _input._isLogDetail) console.log('cmmn wicac DELETE [ ' + _this._input._url+urlParams +' ] result : ',_input._rstData);
                return _input._rstData;
            }            
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
    function parseApiCompleted(input,rstData, response) {
        if(input._isLogDetail) console.log('cmmn wicac parseApiCompleted : ',response);
        rstData.rawData = response;
        if(response && response.data) {
            if(response.data.meta) {
                rstData.page = response.data.meta;
            }
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
    function parseApiError(input,rstData, error) {
        if(input._isLogDetail) console.log('cmmn wicac parseApiError : ',error);
        rstData.isError = true;
        rstData.rawData = error;
        if(error) {
            if (error.response) {
                rstData.status = error.response.status;
                if(error.response.data) {
                    //code :
                    if (error.response.data.status === 'fail') {
                        if(error.response.status == 422) {
                            rstData.msg = error.response.data.errors;
                        } else {
                            rstData.msg = error.response.data.message;
                            if(error.response.statusText != 'Bad Request') {
                                rstData.isAlert = true;       
                            } else {
                                rstData.msg = error.response.data.errors;
                            }
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
    //End of public wica connector

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

    //public wica swal
    /**
      
        import { cmmn } from '@/hooks/cmmn';
        const { wica } = cmmn();


        wica.ntcn(swal)
        .param({}) // 리턴값에 전달 할 데이터
        .title('') // 알림 제목
        .useHtmlText() // HTML 태그 인 경우 활성화
        .icon('E') //E:error , W:warning , I:info , Q:question , S:success
        .useClose() // 닫기 버튼 활성화
        .useBackCancel() // 창 외부 클릭 닫기 활성화
        .labelOk('') //확인 버튼 라벨 변경시
        .labelCancel('') //취소 버튼 라벨 변경시
        .btnBatch('L') //확인 버튼 위치 지정 , L 기본
        .timer(10) //자동 닫기 타이머 설정 , toast 는 미지정시 2초 자동 처리
        .addClassNm('') // 클래스명 변경시 기입, 기본 클래스명 : wica-salert
        .addOption({}) //swal 기타 옵션 추가
        .callback(function(result) {
            console.log(result);
        })
        .toast('<b style="color:red">msg wica toast</b>'); 
        .alert('<b style="color:red">msg wica alert</b>');
        .confirm('<b style="color:red">msg wica confirm</b>');
        .fire('<b style="color:red">msg wica toast</b>'); //toast랑 비슷하며 이미지 넣을 수 있음

        # 유형 1

            wica.ntcn(swal).toast('이용후기가 정상적으로 삭제되었습니다.');

            wica.ntcn(swal).icon('S').title('로그인 성공').fire();

        # 유형 2

            wica.ntcn(swal)
            .icon('I') //E:error , W:warning , I:info , Q:question
            .callback(function(result) {
                if(result.isOk){                                
                    getAllReview(1);                                
                }
            })
            .alert('이용후기가 정상적으로 삭제되었습니다.');
        
        # 유형 3

            wica.ntcn(swal)
            .title('오류가 발생하였습니다.')
            .icon('E') //E:error , W:warning , I:info , Q:question
            .alert('관리자에게 문의해주세요.');

        # 참고 유형
            
            wica.ntcn(swal).useHtmlText().alert('<b style="color:red">message !!</b>');

            wica.ntcn(swal).title('message !!!').alert();

            wica.ntcn(swal).icon('E').title('error message !!!').alert();

            wica.ntcn(swal).icon('E').title('error message !!!').callback(function(result){
                console.log(result);
            }).alert();

        # addOption 값에 넣을 수 있는 항목 정리

            width: 700,
            height: 700,
            padding: 150,
            background: '#fff url(https://image.shutterstock.com/z/stock-vector--exclamation-mark-exclamation-mark-hazard-warning-symbol-flat-design-style-vector-eps-444778462.jpg)',
            imageUrl: 'https://image.shutterstock.com/z/stock-vector--exclamation-mark-exclamation-mark-hazard-warning-symbol-flat-design-style-vector-eps-444778462.jpg',
            imageWidth: 200,
            imageHeight: 200,
            imageAlt: 'Custom image',


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
        fire : function(txt) {
            let _this = this;            
            _this = this.init(_this, txt, true, false);   
            _this._swal.fire(_this._input.opt)
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
                } else if(input.toUpperCase() == 'S') {
                    _this._input._icon = 'success'
                } else if(input.toUpperCase() == 'F') {
                    _this._input._icon = 'fail'
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

// 위카 명  
    const wicaLabel = {        
        title : function() {
            return window.Laravel.appName;
        }
        ,name : function() {
            return window.Laravel.appName;
        }
    } 

    return {
      numberToKoreanUnit,
      formatCurrency,
      amtComma,
      openPostcode,
      closePostcode,
      splitDate,
      getDayOfWeek,
      formatDateAndTime,
      wica,
      wicac,
      wicaLabel,
    }
  }
