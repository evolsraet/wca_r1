import { ref, computed, onMounted, reactive, onUnmounted , inject } from 'vue';

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
        .multipartUpdate() //첨부파일을 포함한 업데이트 요청인 경우
        .param({
            'id' : 1
        }) 
        .search('')
        .where([
            'w1=1',
            'w2=1',
        ]) 
        .whereOr('auctions.status','dlvr,done').whereOr('auctions.car_no','3,2') // where 조건에서 or 처리 (서버 whereIn) , 여러개인 경우 계속 추가
        .whereLike('auctions.addr1','청주시').whereLike('auctions.addr1','대전') // where 조건에서 like 처리 , 여러개인 경우 계속 추가
        //조건문 별도 구성 방법 참조
        .addWhere('auctions.status','dlvr')
        .addOrWhere('auctions.status','done')
        .addLike('auctions.add1','청주시')
        .sprtrOr()
        .sprtrAnd()
        //End 조건문 별도 구성 방법 참조
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


        ### 참조 : 조건문 별도 구성 방법 (작성시 순서대로 매칭됨)

        # 쿼리문 예시 1 : auctions.status = dlvr and auctions.status = done            
            .addWhere('auctions.status','dlvr').addWhere('auctions.status','done')

        # 쿼리문 예시 2 : auctions.status = dlvr or auctions.status = done        
            .addWhere('auctions.status','dlvr').addOrWhere('auctions.status','done')

        # 쿼리문 예시 3 : ( auctions.status = dlvr or auctions.status = done ) and ( auctions.addr1 like %청주시% and auctions.status like %대전% )
            .addWhere('auctions.status','dlvr').addOrWhere('auctions.status','done').sprtrAnd().addLike('auctions.add1','청주시').addLike('auctions.add1','대전')

        # 쿼리문 예시 4 : ( auctions.status = dlvr or auctions.status = done ) or ( auctions.addr1 like %청주시% and auctions.status like %대전% )
            .addWhere('auctions.status','dlvr').addOrWhere('auctions.status','done').sprtrOr().addLike('auctions.add1','청주시').addLike('auctions.add1','대전')

        # 쿼리문 예시 4 : auctions.addr1 like %청주시% and ( auctions.status = dlvr or auctions.status = done )
            .addLike('auctions.add1','청주시').sprtrAnd().addWhere('auctions.status','dlvr').addOrWhere('auctions.status','done')

        # 쿼리문 예시 5 : auctions.addr1 like %청주시% or ( auctions.status = dlvr or auctions.status = done )
            .addLike('auctions.add1','청주시').sprtrOr().addWhere('auctions.status','dlvr').addOrWhere('auctions.status','done')


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
                _whereOr : [],
                _whereLike : [],
                _sprtrMake : [],
                _with : null,
                _doesnthave : null,
                _order : null,
                _page : 1,
                _searchText : null,
                _isUrl : false,
                _isParam : false,
                _isWhere : false,
                _isWhereOr : false,
                _isWhereLike : false,
                _isSprtrMake : false,                
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
                _isMultipartWithUpdate : false,
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
        multipartUpdate : function() {
            let _this = this;
            if(_this._input._isLogDetail) console.log('cmmn wicac [ multipart() with Put (Update) ] 함수 선언 ');
            _this._input._isMultipart = true;
            _this._input._isMultipartWithUpdate = true;
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
        whereOr : function(key,val) {
            let _this = this;
            if(_this._input._isLogDetail) console.log('cmmn wicac [ whereOr(key,val) ] 함수 선언 ',key,val);
            if(key) {
                _this._input._whereOr.push([key,val]);
                _this._input._isWhereOr = true;
                _this._input._isWhere = true;
            }
            return _this;
        },
        whereLike : function(key,val) {
            let _this = this;
            if(_this._input._isLogDetail) console.log('cmmn wicac [ whereLike(key,val) ] 함수 선언 ',key,val);
            if(key) {
                _this._input._whereLike.push([key,val]);
                _this._input._isWhereLike = true;
                _this._input._isWhere = true;
            }
            return _this;
        },
        addWhere : function(key,val) {
            let _this = this;
            if(_this._input._isLogDetail) console.log('cmmn wicac [ addWhere(key,val) ] 함수 선언 ',key,val);
            if(key) {
                _this._input._sprtrMake.push(['addWhere',key,val]);
                _this._input._isSprtrMake = true;
            }
            return _this;
        },
        addOrWhere : function(key,val) {
            let _this = this;
            if(_this._input._isLogDetail) console.log('cmmn wicac [ addOrWhere(key,val) ] 함수 선언 ',key,val);
            if(key) {
                _this._input._sprtrMake.push(['addOrWhere',key,val]);
                _this._input._isSprtrMake = true;
            }
            return _this;
        },
        addLike : function(key,val) {
            let _this = this;
            if(_this._input._isLogDetail) console.log('cmmn wicac [ addLike(key,val) ] 함수 선언 ',key,val);
            if(key) {
                _this._input._sprtrMake.push(['addLike',key,val]);
                _this._input._isAddLike = true;
                _this._input._isSprtrMake = true;
            }
            return _this;
        },
        sprtrOr : function() {
            let _this = this;
            if(_this._input._isLogDetail) console.log('cmmn wicac [ sprtrOr() ] 함수 선언 ');
            _this._input._sprtrMake.push(['sprtrOr']);
            _this._input._isSprtrMake = true;
            return _this;
        },
        sprtrAnd : function() {
            let _this = this;
            if(_this._input._isLogDetail) console.log('cmmn wicac [ sprtrAnd() ] 함수 선언 ');
            _this._input._sprtrMake.push(['sprtrAnd']);
            _this._input._isSprtrMake = true;
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
            if(wicaData.is('wicac_err_429')) {
                console.log('cmmn wicac [ get() ] 함수 선언 실패 : 에러 429 발생으로 거부됨 ');
                return; 
            }
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
            if(wicaData.is('wicac_err_429')) {
                console.log('cmmn wicac [ post() ] 함수 선언 실패 : 에러 429 발생으로 거부됨 ');
                return; 
            }
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
            if(wicaData.is('wicac_err_429')) {
                console.log('cmmn wicac [ put() ] 함수 선언 실패 : 에러 429 발생으로 거부됨 ');
                return; 
            }
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
            if(wicaData.is('wicac_err_429')) {
                console.log('cmmn wicac [ delete() ] 함수 선언 실패 : 에러 429 발생으로 거부됨 ');
                return; 
            }
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
                if(_input._where!=null) {
                    _input._where.forEach(function(item){
                        if(urlParamsWhere) urlParamsWhere += '|';
                        urlParamsWhere += item;
                    });
                }
                if(_input._isWhereOr) {
                    _input._whereOr.forEach((item) => {
                        if(urlParamsWhere) urlParamsWhere += '|';
                        urlParamsWhere += item[0]+':whereIn:'+item[1];
                    });
                }
                if(_input._isWhereLike) {
                    _input._whereLike.forEach((item) => {
                        if(urlParamsWhere) urlParamsWhere += '|';
                        urlParamsWhere += item[0]+':like:'+item[1];
                    });
                }
                if(urlParams) urlParams += '&';
                urlParams += 'where='+urlParamsWhere;
                if(_input._isLogDetail) console.log('cmmn wicac [ parseParam ] where 처리 ',_input._where,' , where='+urlParamsWhere);
            }
            if(_input._isSprtrMake) {
                let urlParamsSprtrMake = '';
                let isSprtr = false;
                _input._sprtrMake.forEach(function(item){
                    let ty = item[0];
                    if(ty == 'addWhere') {
                        let k = item[1];
                        let v = item[2];
                        if(urlParamsSprtrMake && !isSprtr) urlParamsSprtrMake += '|';
                        urlParamsSprtrMake += k+':'+v;
                        isSprtr = false;
                    } else if(ty == 'addOrWhere') {
                        let k = item[1];
                        let v = item[2];
                        if(urlParamsSprtrMake && !isSprtr) urlParamsSprtrMake += '|';
                        urlParamsSprtrMake += k+':orWhere:'+v;
                        isSprtr = false;
                    } else if(ty == 'addLike') {
                        let k = item[1];
                        let v = item[2];
                        if(urlParamsSprtrMake && !isSprtr) urlParamsSprtrMake += '|';
                        urlParamsSprtrMake += k+':like:'+v;
                        isSprtr = false;
                    } else if(ty == 'sprtrOr') {
                        urlParamsSprtrMake += "_or_";
                        isSprtr = true;
                    } else if(ty == 'sprtrAnd') {
                        urlParamsSprtrMake += "_and_";
                        isSprtr = true;
                    } else {
                        // not working
                    }
                });
                if(urlParams) urlParams += '&';
                urlParams += 'where='+urlParamsSprtrMake;
                if(_input._isLogDetail) console.log('cmmn wicac [ parseParam ] where sprtrMake 처리 ',_input._sprtrMake,' , where='+urlParamsSprtrMake);
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
            if(_input._isPageLimit && _input._pageLimit > 0) {
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
                    if(_input._isMultipartWithUpdate) urlParamsData.append('_method', 'PUT');
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
                let urlParams = '';
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
                        } else if(error.response.status == 429) {
                            if(!wicaData.is('wicac_err_429')) {
                                wicaData.save('wicac_err_429',true);
                                alert('단시간 많은 호출로 인해 요청이 거부되었습니다.\n차단이 해제되려면 약 1분 뒤 다시 시도해주세요.');
                                setTimeout(function() {
                                    wicaData.del('wicac_err_429');
                                },800);
                            }
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
                confirmButtonColor: '#ef4444',
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

    //공통 전역 라벨링
    const wicaLabel = {        
        title : function() {
            return window.Laravel.appName;
        }
        ,name : function() {
            return window.Laravel.appName;
        }
    } 
    //End of 공통 전역 라벨링


    //public wicas enum data
    /**
    
        # 필수 선언

        import { reactive  } from 'vue';
        import { useStore } from 'vuex';
        const store = useStore();
    
        import { cmmn } from '@/hooks/cmmn';
        const { wicas } = cmmn();


        # 호출 방법 ( enums : wicas.enum() , fields : wicas.field() )
        
        wicas.enum(store) // api/lib/enums 관련
        wicas.field(store) // api/lib/fields 관련
        .excl('dlvr','wait') // 기입된 필드는 제거
        .perm('dlvr','wait') // 기입된 필드만 남김
        .add('dlvrNew','입금대기').add('doneNew','입금완료') // 필드 추가
        .addFirst('all','전체').addFirst('','전체2') // 정렬 순서(desc,asc 선언) 영향 없이 입력 순서대로 필드 맨 위에 위치
        .change('dlvr','입금대기').change('done','입금완료') // 기존 필드 값 변경
        .changeKey('dlvr','dlvr2').changeKey('done','done2') // 기존 필드 키 명칭 변경
        .toProxy(reactive) // 리턴 데이터를 proxy object 로 변환
        .callback(function(item){ //필드 갯수만큼 개별 출력
            console.log(item);
        })
        .toLabel('wait') // 입력값 하나에 대해서 'wait' 를 기입하면 '선택대기' 를 리턴
        .toCode('선택대기') // 입력값 하나에 대해서 '선택대기' 를 기입하면 'wait' 를 리턴
        .DescKey() // 키 기준 c,b,a 순
        .DescVal() // 값 기준 다,나,가 순
        .ascKey() // 키 기준 a,b,c 순
        .ascVal() // 값 기준 가,나,다 순
        
        .auctions(); // enums data, fields data
        .users(); // enums data, fields data
        .dealers(); // enums data, fields data



        # 호출 예시 ( enums : wicas.enum() , fields : wicas.field() )

        console.log(wicas.enum(store).auctions()); // 예시 : { "done": "경매완료", "wait": "선택대기", "ing": "경매진행", "dlvr": "탁송중" }
        console.log(wicas.enum(store).users());
        console.log(wicas.enum(store).dealers());
        console.log(wicas.enum(store).excl('dlvr','wait').auctions()); // { "done": "경매완료", "ing": "경매진행" }
        console.log(wicas.enum(store).perm('dlvr','wait').auctions()); // { "wait": "선택대기", "dlvr": "탁송중" }
        console.log(wicas.enum(store).add('k1','aa').add('k2',2).change('dlvr','입금대기').changeKey('done','done2').auctions()); // { "done2": "경매완료", "wait": "선택대기", "ing": "경매진행", "dlvr": "입금대기", "k1": "aa" , "k2" : 2 }
        console.log(wicas.enum(store).toLabel('wait').auctions()); // "선택대기"
        console.log(wicas.enum(store).toCode('선택대기').auctions()); // "wait"
        console.log(wicas.enum(store).addFirst('all','전체').auctions()); // { "all": "전체", "done": "경매완료", "wait": "선택대기", "ing": "경매진행", "dlvr": "탁송중" }

        let rdata = wicas.enum(store).excl('dlvr','wait').callback(function(item){
          console.log(item); // {"key":"done","val":"경매완료"} ...
        }).auctions();
        console.log(rdata); 
        

        # 필요한 테이블 enums , fields 는 별도 추가해야함.
        # ( js/store/enums.js 또는 js/store/fields.js ) 에 loopLabel 값에도 추가
        # (views/login/Login.vue) 에서 await store.dispatch("enums/getData"); 가 로그인시 호출됨
        # (views/login/Login.vue) 에서 await store.dispatch("fields/getData"); 가 로그인시 호출됨
      
     */
    const wicas = {        
        _store : null,
        _input : null,
        enum : function(_store) {
            let newObj = this.init(_store);
            newObj._input._isEnum = true;
            return newObj;
        },
        field : function(_store) {
            let newObj = this.init(_store);
            newObj._input._isField = true;
            return newObj;
        },
        init : function(_store) {
            let newObj = Object.create(this);
            newObj._store = _store;            
            newObj._input = {
                _isEnum : false,
                _isField : false,
                isExcl : false,
                isPerm : false,
                isAdd : false,
                isAddFirst : false,
                isChange : false,
                isChangeKey : false,
                isToProxy : false,
                isReturn : false,
                isToLabel : false,
                isToCode : false,
                isDescKey : false,
                isAscKey : false,
                isDescVal : false,
                isAscVal : false,
                _excl : null,
                _perm : null,
                _add : {},
                _addFirst : {},
                _change : {},
                _changeKey : {},
                _toProxy : null,
                _callback : null,
                _toLabel : null,
                _toCode : null,
            }
            return newObj;
        },        
        excl : function(...input) {
            let _this = this;
            _this._input.isExcl = true;
            _this._input._excl = input;
            return _this;
        },
        perm : function(...input) {
            let _this = this;
            _this._input.isPerm = true;
            _this._input._perm = input;
            return _this;
        },
        add : function(key,val) {
            let _this = this;
            _this._input.isAdd = true;
            if(key) {
                _this._input._add[key] = val;
            }
            return _this;
        },
        addFirst : function(key,val) {
            let _this = this;
            _this._input.isAddFirst = true;
            if(key) {
                _this._input._addFirst[key] = val;
            }
            return _this;
        },
        change : function(key,val) {
            let _this = this;
            _this._input.isChange = true;
            if(key) {
                _this._input._change[key] = val;
            }
            return _this;
        },
        changeKey : function(key,val) {
            let _this = this;
            _this._input.isChangeKey = true;
            if(key) {
                _this._input._changeKey[key] = val;
            }
            return _this;
        },
        toProxy : function(input) {
            let _this = this;
            _this._input.isToProxy = true;
            if(input) {
                _this._input._toProxy = input
            }
            return _this;
        },
        callback : function(input) {
            let _this = this;
            _this._input._callback = input;
            if(input) {
                _this._input.isReturn = true;
            }
            return _this;
        },
        toLabel : function(input) {
            let _this = this;
            _this._input.isToLabel = true;
            if(input) {
                _this._input._toLabel = input;
            }
            return _this;
        },
        toCode : function(input) {
            let _this = this;
            _this._input.isToCode = true;
            if(input) {
                _this._input._toCode = input;
            }
            return _this;
        },
        toCode : function(input) {
            let _this = this;
            _this._input.isToCode = true;
            if(input) {
                _this._input._toCode = input;
            }
            return _this;
        },
        descKey : function() {
            let _this = this;
            _this._input.isDescKey = true;
            return _this;
        },
        ascKey : function() {
            let _this = this;
            _this._input.isAscKey = true;
            return _this;
        },
        descVal : function() {
            let _this = this;
            _this._input.isDescVal = true;
            return _this;
        },
        ascVal : function() {
            let _this = this;
            _this._input.isAscVal = true;
            return _this;
        },
        //enums 목록
        auctions : function() {
            let _this = this;
            let data;
            if(_this._input._isEnum) {
                let rdata =  this.deepClone(this._store.getters['enums/data']['auctions']);
                data = rdata.status;
            } else if(_this._input._isField) {
                data =  this.deepClone(this._store.getters['fields/data']['auctions']);
            } else {
                data = null;
            }
            return this.processPublic(_this._input,data);
        },
        users : function() {
            let _this = this;
            let data;
            if(_this._input._isEnum) {
                let rdata =  this.deepClone(this._store.getters['enums/data']['users']);
                data = rdata.status;
            } else if(_this._input._isField) {
                data =  this.deepClone(this._store.getters['fields/data']['users']);
            } else {
                data = null;
            }
            return this.processPublic(_this._input,data);
        },
        dealers : function(input) {
            let _this = this;
            let data;
            if(_this._input._isEnum) {
                let rdata =  this.deepClone(this._store.getters['enums/data']['dealers']);
                data = rdata.status;
            } else if(_this._input._isField) {
                data =  this.deepClone(this._store.getters['fields/data']['dealers']);
            } else {
                data = null;
            }
            return this.processPublic(_this._input,data);
        },
        //utils
        deepClone : function(input) {
            return JSON.parse(JSON.stringify(input));
        },
        filtering : function(input,keys) {
            return Object.keys(input)
            .filter(key => keys.includes(key))
            .reduce((obj, key) => {
                obj[key] = input[key];
                return obj;
            }, {});
        },
        remove : function(input,keys) {
            keys.forEach(val => {
                delete input[val];
            });
            return input;
        },
        processPublic : function(_input,data) {
            if(_input.isAdd) {
                Object.assign(data, _input._add);
            }
            let d;
            if(_input.isExcl) {
                d = this.remove(data,_input._excl);                
            } else if(_input.isPerm) {
                d = this.filtering(data,_input._perm);
            } else {
                d = data;
            }
            if(_input.isChangeKey) {
                Object.keys(_input._changeKey).forEach(key => {
                    const k = _input._changeKey[key];
                    d[k] = d[key];
                    delete d[key];
                });
            }
            if(_input.isChange) {
                Object.keys(_input._change).forEach(key => {
                    if (d.hasOwnProperty(key)) {
                        d[key] = _input._change[key];
                    }
                });
            }
            if(_input.isDescKey) {
                const sortedEntries = Object.entries(d).sort(([keyA], [keyB]) => keyB.localeCompare(keyA));
                d = Object.fromEntries(sortedEntries);
            } else if(_input.isDescVal) {
                const sortedEntries = Object.entries(d).sort(([, valueA], [, valueB]) => valueB.localeCompare(valueA));
                d = Object.fromEntries(sortedEntries);
            } else if(_input.isAscKey) {
                const sortedEntries = Object.entries(d).sort(([keyA], [keyB]) => keyA.localeCompare(keyB));
                d = Object.fromEntries(sortedEntries);    
            } else if(_input.isAscVal) {
                const sortedEntries = Object.entries(d).sort(([, valueA], [, valueB]) => valueA.localeCompare(valueB));
                d = Object.fromEntries(sortedEntries);
            }
            if(_input.isAddFirst) {
                d = Object.assign({}, _input._addFirst, d);
            }
            if(_input.isReturn) {
                for (let key in d) {
                    _input._callback({key:key,val:d[key]});
                };
            }
            if(_input.isToLabel) {
                return d[_input._toLabel];
            } else if(_input.isToCode) {
                return Object.keys(d).find(key => d[key] === _input._toCode);
            } else {
                if(_input.isToProxy) {
                    return _input._toProxy(d);
                } else {
                    return d;
                }
            }  
        }
    } 
    //End of public wicas enum data

    //public wicaData
    /**
     
        # 필수 선언
            
            import { cmmn } from '@/hooks/cmmn';
            const { wicaData } = cmmn();

        # 사용 방법

            // wicaData 내 모든 저장 값 초기화(삭제)
            wicaData.clear();

            // key 존재 여부 확인(true,false 리턴)
            wicaData.is('key1');

            // key 값 저장하기 (각 유형별 저장)
            wicaData.save('key1',{d1:11,d2:'bb'});
            wicaData.save('key2',['d1','d2','d3']);
            wicaData.save('key3',100);
            wicaData.save('key4','data1');
            wicaData.save('key5',true);

            // key 값 불러오기(저장 유형에 따라 리턴)
            wicaData.load('key1'); // {d1:11,d2:'bb'}
            wicaData.load('key2'); // ['d1','d2','d3']
            wicaData.load('key3'); // 숫자 100
            wicaData.load('key4'); // 문자 'data1'
            wicaData.load('key5'); // true,false

            // key 로 값 삭제
            wicaData.del('key1');
      
     */
    const wicaData = {
        _publicKey : 'wicald_data_',
        _saveInfo : {},
        /*
        data : function() {
            let newObj = Object.create(this);
            return newObj;
        },
        */
        save : function(key,val) {
            if(Array.isArray(val)) {
                this._saveInfo[key] = {type:'array'};
                window.localStorage.setItem(this._publicKey+key, JSON.stringify(val));
            } else if(typeof val === 'object' && val !== null) {
                this._saveInfo[key] = {type:'object'};
                window.localStorage.setItem(this._publicKey+key, JSON.stringify(val));
            } else if(typeof val === 'number') {
                this._saveInfo[key] = {type:'number'};
                window.localStorage.setItem(this._publicKey+key,val);
            } else if(typeof val === 'boolean') {
                this._saveInfo[key] = {type:'boolean'};
                window.localStorage.setItem(this._publicKey+key,val);
            } else {
                this._saveInfo[key] = {type:'default'};
                window.localStorage.setItem(this._publicKey+key,val);
            }            
        },
        load : function(key) {
            if(this.is(key)) {
                let ty = this._saveInfo[key].type;
                if(ty == 'object' || ty == 'array') {
                    return JSON.parse(window.localStorage.getItem(this._publicKey+key));
                } else if(ty == 'number') {
                    return parseInt(window.localStorage.getItem(this._publicKey+key));
                } else if(ty == 'boolean') {
                    return window.localStorage.getItem(this._publicKey+key).toLowerCase()==='true';
                } else {
                    return window.localStorage.getItem(this._publicKey+key);
                }
            } else {
                return null;
            }
        },
        del : function(key) {
            if(this.is(key))
                window.localStorage.removeItem(this._publicKey+key);
        },
        clear : function() {
            for(const key in window.localStorage) {
                if(window.localStorage.hasOwnProperty(key) && key.search(this._publicKey) !== -1) {
                    window.localStorage.removeItem(key);
                }
            }
        },
        is : function(key) {
            return window.localStorage.hasOwnProperty(this._publicKey+key);
        }
    }
    //End of //public wicaData

    /**
     * 
     *  timer = setInterval(() => {
        currentTime.value = new Date();
        updateAuctionTimes(auctionsData.value);
        updateAuctionTimes(favoriteAuctionsData.value);
        updateAuctionTimes(bidsData.value);
    }, 1000);} 
    */
    const updateAuctionTimes = (auction) => {
        auction.forEach((auction) => {
            auction.timeLeft = calculateTimeLeft(auction);
        });
    };

    const padZero = (num) => {
        return num < 10 ? '0' + num : num;
      };

    //시간 카운트
    const calculateTimeLeft = (auction) => {
        const currentTime = ref(new Date());
        const finalAtDate = new Date(auction.final_at);
        const diff = finalAtDate.getTime() - currentTime.value.getTime();
        if (auction.status !== 'ing' || !auction.final_at || diff < 0) {
            return {
                days: 0,
                hours: '00',
                minutes: '00',
                seconds: '00'
            };
        }
        return {
            days: Math.floor(diff / (24 * 3600000)),
            hours: padZero(Math.floor((diff % (24 * 3600000)) / 3600000)),
            minutes: padZero(Math.floor((diff % 3600000) / 60000)),
            seconds: padZero(Math.floor((diff % 60000) / 1000)),
        };
    };
    //End of updateAuctionTimes

    return {
      numberToKoreanUnit,
      formatCurrency,
      amtComma,
      openPostcode,
      closePostcode,
      splitDate,
      getDayOfWeek,
      formatDateAndTime,
      updateAuctionTimes,
      wica,
      wicac,
      wicaLabel,
      wicas,
      wicaData,
    }
  }
