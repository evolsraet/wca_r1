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
                // 기존 검색 창이 있다면 제거
                element.innerHTML = '';

                // 닫기 버튼 추가
                const closeButton = document.createElement('button');
                closeButton.innerHTML = '닫기';
                closeButton.style.cssText = `
                    position: absolute;
                    top: 10px;
                    right: 10px;
                    z-index: 1000;
                    padding: 5px 10px;
                    background-color: #fff;
                    border: 1px solid #ddd;
                    border-radius: 4px;
                    cursor: pointer;
                `;
                closeButton.onclick = () => {
                    element.innerHTML = '';
                    element.style.display = 'none';
                    resolve(null);
                };
                element.appendChild(closeButton);

                new window.daum.Postcode({
                    oncomplete: function(data) {
                        // 주소 선택 후 검색 창 제거
                        element.innerHTML = '';
                        element.style.display = 'none';
                        resolve({
                            zonecode: data.zonecode,
                            address: data.address
                        });
                    },
                    // 주소 검색 창이 버튼 아래에 나타나도록 설정
                    onresize: function(size) {
                        if (element) {
                            element.style.width = size.width + 'px';
                            element.style.height = size.height + 'px';
                        }
                    },
                    width: '100%',
                    height: '400px'
                }).embed(element);
            });
        } catch (error) {
            console.error('주소 검색 오류:', error);
            alert('주소 검색 서비스를 불러오는 중입니다. 잠시 후 다시 시도해주세요.');
        }
    }
};