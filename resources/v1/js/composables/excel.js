//URL 인코딩 : iframe 요청 오류 , 권한: 세션쿠키
export const downloadExcel = async (endpoint, orderColumn = 'created_at', orderDirection = 'asc', fileName = 'data.xlsx', stat = '', role = '', search_text = '') => {
    try {
        let url = `/excelDown/${endpoint}`;

        if (stat === 'all') {
            stat = '';
        }
        if (role === 'all') {
            role = '';
        }

        let queryParameters = [];

        if (endpoint === 'users') {
            if (stat) {
                queryParameters.push(`where=users.status:${encodeURIComponent(stat)}`);
            }
            if (role) {
                queryParameters.push(`where=users.roles:${encodeURIComponent(role)}`);
            }
        } else if (endpoint === 'auctions' && stat) {
            queryParameters.push(`where=auctions.status:${encodeURIComponent(stat)}`);
        } else if (endpoint === 'reviews' && stat) {
            queryParameters.push(`where=reviews.star:${encodeURIComponent(stat)}`);
        }

        if (search_text) {
            queryParameters.push(`search_text=${encodeURIComponent(search_text)}`);
        }

        queryParameters.push(`order_column=${encodeURIComponent(orderColumn)}`);
        queryParameters.push(`order_direction=${encodeURIComponent(orderDirection)}`);

        if (endpoint === 'reviews') {
            queryParameters.push(`with=dealer,auction,user`);
        }

        if (queryParameters.length > 0) {
            url += `?${queryParameters.join('&')}`;
        }

        // iframe생성
        const downloadFrame = document.createElement('iframe');
        downloadFrame.style.display = 'none';
        downloadFrame.src = url;
        document.body.appendChild(downloadFrame);

        //iframe 제거
        setTimeout(() => {
            document.body.removeChild(downloadFrame);
        }, 1000);

    } catch (error) {
        console.error("엑셀 파일 다운로드 실패:", error);
    }
};
