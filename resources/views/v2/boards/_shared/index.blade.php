@php
$boardInfo = Wca::board_menu($board->id);
@endphp

@if(isset($boardInfo['listStyle']) && $boardInfo['listStyle'])
    @include('v2.boards._shared.lists.' . $boardInfo['listStyle'])
@else
    @include('v2.boards._shared.lists.basicList')
@endif 
