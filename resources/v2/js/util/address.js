export const address = {
    async openPostcode(elementId) {
        try {
            // 주소 검색 버튼 클릭 시에만 스크립트 로드
            if (!window.daum || !window.daum.Postcode) {
                await new Promise((resolve, reject) => {
                    const script = document.createElement('script');
                    script.src = '//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js';
                    script.async = true;
                    script.onload = resolve;
                    script.onerror = () => reject(new Error('Daum 주소 검색 스크립트 로드 실패'));
                    document.head.appendChild(script);
                });
            }

            return new Promise((resolve) => {
                const element = document.getElementById(elementId);

                // 현재 스크롤 위치 저장
                const currentScroll = Math.max(document.body.scrollTop, document.documentElement.scrollTop);

                // 기존 요소 제거 및 새로 생성
                const newElement = document.createElement('div');
                newElement.id = elementId;
                newElement.style.position = 'relative';
                newElement.style.height = '454px';
                newElement.style.border = '2px solid black';
                newElement.style.marginBottom = '20px';
                newElement.style.display = 'none';
                element.parentNode.replaceChild(newElement, element);

                // 닫기 버튼 추가
                const closeButton = document.createElement('img');
                closeButton.src = '//t1.daumcdn.net/postcode/resource/images/close.png';
                closeButton.style.cssText = `
                    cursor: pointer;
                    position: absolute;
                    right: 0px;
                    top: -1px;
                    z-index: 1;
                `;
                closeButton.onclick = (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    newElement.style.display = 'none';
                    // 스크롤 위치 복원
                    document.body.scrollTop = currentScroll;
                    resolve(null);
                };
                newElement.appendChild(closeButton);

                new window.daum.Postcode({
                    oncomplete: function(data) {
                        // 주소 선택 후 검색 창 숨기기
                        newElement.style.display = 'none';

                        // 주소 정보 처리
                        let addr = '';
                        let extraAddr = '';

                        // 사용자가 선택한 주소 타입에 따라 해당 주소 값을 가져온다.
                        if (data.userSelectedType === 'R') {
                            addr = data.roadAddress;
                        } else {
                            addr = data.jibunAddress;
                        }

                        // 사용자가 선택한 주소가 도로명 타입일때 참고항목을 조합한다.
                        if(data.userSelectedType === 'R'){
                            if(data.bname !== '' && /[동|로|가]$/g.test(data.bname)){
                                extraAddr += data.bname;
                            }
                            if(data.buildingName !== '' && data.apartment === 'Y'){
                                extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
                            }
                            if(extraAddr !== ''){
                                extraAddr = ' (' + extraAddr + ')';
                            }
                        }

                        // 스크롤 위치 복원
                        document.body.scrollTop = currentScroll;

                        resolve({
                            zonecode: data.zonecode,
                            address: addr,
                            extraAddress: extraAddr
                        });
                    },
                    // 주소 검색 창이 버튼 아래에 나타나도록 설정
                    onresize: function(size) {
                        if (newElement) {
                            // Daum 레이어에 스크롤 적용
                            const daumLayer = document.querySelector('#__daum__layer_1');
                            if (daumLayer) {
                                daumLayer.style.overflow = 'auto';
                                daumLayer.style.maxHeight = '450px';
                            }
                        }
                    },
                    width: '100%',
                    height: '450px'
                }).embed(newElement);

                // 검색 창 표시
                newElement.style.display = 'block';
            });
        } catch (error) {
            console.error('주소 검색 오류:', error);
            alert('주소 검색 서비스를 불러오는 중입니다. 잠시 후 다시 시도해주세요.');
        }
    }
};
