<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>결제 테스트 페이지</title>
</head>
<body>

   
    <script src="https://pay.nicepay.co.kr/v1/js/"></script>

    <script>
    function serverAuth() {
        AUTHNICE.requestPay({
            clientId: '{{ env('NICE_PAY_TEST_CLIENT_KEY') }}',
            method: 'card',
            orderId: random(),
            amount: 300,
            goodsName: '나이스페이-상품',
            returnUrl: 'http://localhost/api/payment/result',
            fnError: function (result) {
            alert('고객용메시지 : ' + result.msg + '\n개발자확인용 : ' + result.errorMsg + '')
            }
        });
    }

    //Test orderId 생성
    const random = (length = 8) => {
        return Math.random().toString(16).substr(2, length);
    };	
    </script>
    
    <button onclick="serverAuth()">결제하기</button>

</body>
</html>