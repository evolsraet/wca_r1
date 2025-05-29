<div class="container my-5">
  <h3 class="text-center fw-bold mb-5">내 차 판매, 이렇게 진행돼요.</h3>
  <div class="row justify-content-center align-items-center g-4 process-steps">

    @php
      $steps = [
        ['step' => '01', 'title' => '경매신청', 'desc' => ['차량정보 입력 후', '경매 신청'], 'img' => 'contract.png'],
        ['step' => '02', 'title' => '차량진단', 'desc' => ['차량 진단 및', '경매 등록'], 'img' => 'car-insurance.png'],
        ['step' => '03', 'title' => '경매', 'desc' => ['딜러 입찰 후', '낙찰자 선정'], 'img' => 'bid.png'],
        ['step' => '04', 'title' => '탁송', 'desc' => ['경매대금 입금 및', '서류 전달, 탁송'], 'img' => 'car-wash.png'],
        ['step' => '05', 'title' => '사후처리', 'desc' => ['클레임 처리 및', '이전'], 'img' => 'care.png'],
      ];
    @endphp

    @foreach ($steps as $index => $step)
      <div class="col-6 col-md-2 d-flex flex-column align-items-center text-center">
        <div class="step-circle mb-2">
          <img src="{{ asset('images/' . $step['img']) }}" alt="{{ $step['title'] }}">
        </div>
        <div class="badge bg-light text-dark fw-semibold mb-2">STEP {{ $step['step'] }}</div>
        <div class="fw-bold mb-1">{{ $step['title'] }}</div>
        @foreach ($step['desc'] as $line)
          <div class="small text-muted">{{ $line }}</div>
        @endforeach
      </div>
    @endforeach

  </div>
</div>