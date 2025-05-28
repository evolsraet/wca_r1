@extends('v2.layouts.app')

@section('title', 'Split Layout 대시보드')

@section('content')
    <x-layouts.split
        leftClass="col-lg-7"
        rightClass="col-lg-5"
        leftContainerClass="p-3 bg-light"
        rightContainerClass="p-3"
        :initialRightPanelOpen="false">

        <x-slot:leftContent>
            <p>이 부분은 좌측 패널의 내용입니다. PC에서는 <code>leftClass</code> (예: col-lg-7)에 정의된 너비를 가집니다.</p>
            <p>모바일에서는 이 영역이 주 컨텐츠로 표시됩니다.</p>
            @for ($i = 1; $i <= 15; $i++)
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">좌측 아이템 {{ $i }}</h5>
                        <p class="card-text">스크롤 테스트를 위한 더미 컨텐츠입니다. 좌측 패널의 내용이 길어질 경우 이 영역이 스크롤됩니다.</p>
                    </div>
                </div>
            @endfor
        </x-slot:leftContent>

        <x-slot:rightContent>
            <p>이 부분은 우측 패널의 내용입니다.</p>
            <p>PC에서는 <code>rightClass</code> (예: col-lg-5)에 정의된 너비로 항상 표시됩니다.</p>
            <p>모바일에서는 하단의 "더 보기" 버튼을 통해 이 영역을 슬라이드 업/다운하여 토글할 수 있습니다.</p>
            @for ($i = 1; $i <= 15; $i++)
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">우측 아이템 {{ $i }}</h5>
                        <p class="card-text">스크롤 테스트를 위한 더미 컨텐츠입니다. 우측 패널의 내용이 길어질 경우 이 영역이 스크롤됩니다.</p>
                    </div>
                </div>
            @endfor
        </x-slot:rightContent>

    </x-layouts.split>
@endsection
