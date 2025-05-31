@php
    $cars = [
        [
            'image' => asset('images/sample-car-01.jpg'),
            'number' => '68거1375',
            'status' => '탁송중',
            'status_class' => 'status-tag-blue'
        ],
        [
            'image' => asset('images/sample-car-02.jpg'),
            'number' => '312저4392',
            'status' => '선택완료',
            'status_class' => 'status-tag-purple'
        ]
    ];
@endphp

<div class="car-wrapper">
    <div class="car-header">
        <h5>내 매물관리</h5>
        <a href="#" class="car-all-link">전체보기 &gt;</a>
    </div>

    <ul class="car-list">
        @foreach ($cars as $car)
            <li class="car-item">
                <div class="car-thumb">
                    <img src="{{ $car['image'] }}" alt="{{ $car['number'] }}">
                </div>
                <div class="car-number">{{ $car['number'] }}</div>
                <div class="car-status {{ $car['status_class'] }}">{{ $car['status'] }}</div>
            </li>
        @endforeach
    </ul>

    <div class="car-add-box">
        <a href="#" class="btn btn-dark w-100">
            새 차량 등록하기 <span class="plus-icon">+</span>
        </a>
    </div>
</div>