<div class="delivery-guide-box py-4">
    <div class="fw-bold fs-5 mb-3">탁송 서비스 이용고객안내</div>

    <ul class="list-unstyled m-0">
        @php
            $guides = [
                '키와 서류를 탁송기사에게 전달해 주세요.',
                '탁송기사가 서류를 수령하면 서류접수<br>완료 처리 버튼을 클릭합니다.',
                '차량대금이 나이스에서 고객 계좌로 송부되어집니다.',
                '경매완료 처리 됩니다.',
            ];
        @endphp

        @foreach($guides as $i => $text)
        <li class="d-flex align-items-start mb-3">
            <div class="step-circle me-3">{{ $i + 1 }}</div>
            <div class="step-text text-dark small">{!! $text !!}</div>
        </li>
        @endforeach
    </ul>
</div>