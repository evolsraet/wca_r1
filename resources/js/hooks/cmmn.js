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
            _param: null
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
    

    return {
      numberToKoreanUnit,
      amtComma,
      openPostcode,
      closePostcode,
      callApi,
    }
  }
