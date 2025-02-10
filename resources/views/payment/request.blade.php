<!DOCTYPE html>
<html>
<head>
<title>위카옥션 가상계좌 결제안내</title>
<meta charset="utf-8">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<style>
	html,body {height: 100%;}
</style>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-sm-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white text-center">
                        <h3>가상계좌 결제안내</h3>
                    </div>
                    <div class="card-body">
                        <p class="fs-5">상품명 : <strong>{{ $GoodsName }}</strong></p>
                        <p class="fs-5">결제 금액 : <strong>{{ number_format($Amt) }} 원</strong></p>
                        <p class="fs-5">은행명 : <strong>{{ $VbankBankName }}</strong></p>
                        <p class="fs-5">계좌번호 : <strong>{{ $VbankNum }}</strong></p>
                        <p class="fs-5">예금만료일 : <strong>{{ $VbankExpDate }}</strong></p>
                        <p class="fs-5">구매자 : {{ $BuyerName }}</p>
                        <p class="fs-5">구매자 연락처 : {{ $BuyerTel }}</p>
                        <p class="fs-5">구매자 이메일 : {{ $BuyerEmail }}</p>
                        <div class="d-grid">
                            <a href="#" class="btn btn-success btn-lg" onClick="window.close();">완료</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>