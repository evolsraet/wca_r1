<?php
header("Content-Type:text/html; charset=utf-8;"); 
/*
*******************************************************
* <결제요청 파라미터>
* 결제시 Form 에 보내는 결제요청 파라미터입니다.
* 샘플페이지에서는 기본(필수) 파라미터만 예시되어 있으며, 
* 추가 가능한 옵션 파라미터는 연동메뉴얼을 참고하세요.
*******************************************************
*/  

$merchantKey = env('NICE_PAY_CLIENT_KEY'); // 상점키
$MID         = env('NICE_PAY_CLIENT_ID'); // 상점아이디
$goodsName   = $auctionName; // 결제상품명
$price       = $auctionPrice; // 결제상품금액
// $buyerName   = ""; // 구매자명 
// $buyerTel	 = ""; // 구매자연락처
// $buyerEmail  = ""; // 구매자메일주소        
$moid        = $orderId; // 상품주문번호                     
$returnURL	 = "/api/payment/result2"; // 결과페이지(절대경로) - 모바일 결제창 전용
/*
*******************************************************
* <해쉬암호화> (수정하지 마세요)
* SHA-256 해쉬암호화는 거래 위변조를 막기위한 방법입니다. 
*******************************************************
*/ 
$ediDate = date("YmdHis");
$hashString = bin2hex(hash('sha256', $ediDate.$MID.$price.$merchantKey, true));
?>
<!DOCTYPE html>
<html>
<head>
<title>위카옥션 결제 페이지</title>
<meta charset="utf-8">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<style>
	html,body {height: 100%;}
	form {overflow: hidden;}
</style>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://pg-web.nicepay.co.kr/v3/common/js/nicepay-pgweb.js" type="text/javascript"></script>
<script type="text/javascript">
//결제창 최초 요청시 실행됩니다.
function nicepayStart(){
	goPay(document.payForm);
}

//[PC 결제창 전용]결제 최종 요청시 실행됩니다. <<'nicepaySubmit()' 이름 수정 불가능>>
function nicepaySubmit(){
	document.payForm.submit();
}

//[PC 결제창 전용]결제창 종료 함수 <<'nicepayClose()' 이름 수정 불가능>>
function nicepayClose(){
	alert("결제가 취소 되었습니다");
}

document.addEventListener("DOMContentLoaded", function () {
      nicepayStart();
});

</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<form name="payForm" method="post" action="http://localhost/api/payment/request">
<input type="hidden" name="PayMethod" value="VBANK">
<input type="hidden" name="MID" value="{{$MID}}">
<input type="hidden" name="VbankExpDate" value="{{$vbankExpDate}}">
<input type="hidden" name="GoodsName" value="{{$goodsName}}">
<input type="hidden" name="Amt" value="{{$price}}">
<input type="hidden" name="BuyerName" value="{{$buyerName}}">
<input type="hidden" name="BuyerEmail" value="{{$buyerEmail}}">
<input type="hidden" name="BuyerTel" value="{{$buyerTel}}">
<input type="hidden" name="ReturnURL" value="{{$returnURL}}">
<input type="hidden" name="Moid" value="{{$moid}}">
<!-- 옵션 -->	 
<input type="hidden" name="GoodsCl" value="1"/>						<!-- 상품구분(실물(1),컨텐츠(0)) -->
<input type="hidden" name="TransType" value="0"/>					<!-- 일반(0)/에스크로(1) --> 
<input type="hidden" name="CharSet" value="utf-8"/>				<!-- 응답 파라미터 인코딩 방식 -->
<input type="hidden" name="ReqReserved" value=""/>					<!-- 상점 예약필드 -->
			
<!-- 변경 불가능 -->
<input type="hidden" name="EdiDate" value="<?php echo($ediDate)?>"/>			<!-- 전문 생성일시 -->
<input type="hidden" name="SignData" value="<?php echo($hashString)?>"/>	<!-- 해쉬값 -->

<div class="container mt-5">
	<div class="row justify-content-center">
		<div class="col-md-6 col-sm-12">
			<div class="card shadow">
				<div class="card-header bg-primary text-white text-center">
					<h3>결제 안내</h3>
				</div>
				<div class="card-body">
					<p class="fs-5">상품명 : <strong>{{ $goodsName }}</strong></p>
					<p class="fs-5">결제 금액 : <strong>{{ number_format($price) }} 원</strong></p>
					<div class="d-grid">
						<a href="#" class="btn btn-success btn-lg" onClick="nicepayStart(); return false;">결제하기</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</form>

</body>
</html>