<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>위카옥션 결제 페이지</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-sm-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white text-center">
                        <h3>결제 금액</h3>
                    </div>
                    <div class="card-body">
                        <p class="fs-5">상품명 : <strong>{{ $auctionName }}</strong></p>
                        <p class="fs-5">결제 금액 : <strong>{{ number_format($auctionPrice) }} 원</strong></p>
                        <div class="d-grid">
                            <button class="btn btn-success btn-lg" onclick="serverAuth()">결제하기</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://pay.nicepay.co.kr/v1/js/"></script>

    <script>
    function serverAuth() {
        AUTHNICE.requestPay({
            clientId: '{{ env('NICE_PAY_TEST_CLIENT_KEY') }}',
            method: 'card',
            orderId: '{{ $orderId }}',
            amount: '{{ $auctionPrice }}',
            goodsName: '{{ $auctionName }}',
            bid: '{{ $bid }}',
            returnUrl: 'http://localhost/api/payment/result',
            fnError: function (result) {
                alert('고객용메시지 : ' + '결제를 취소 하셨습니다.' + '\n개발자확인용 : ' + result.errorMsg + '')
            }
        });
    }
    </script>

</body>
</html>