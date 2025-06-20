@php
    $title = $title ?? '거래는 어떠셨나요?';
    $message = $message ?? '거래 후기를 남겨주세요.';
    $button1 = $button1 ?? '후기 남기기';
    $button1Link = $button1Link ?? '#';
    $button2 = $button2 ?? '명의이전 서류 확인';
    $button2Link = $button2Link ?? '#';
@endphp
<div class="py-4 p-2">

    <div class="mb-3">
        <div class="fw-bold fs-5">{{ $title }}</div>
        <div class="text-muted small">{{ $message }}</div>
    </div>

    <div class="mb-3">
        <a href="{{ $button1Link }}" class="btn btn-danger w-100 py-2 fw-semibold" type="button">
            {{ $button1 }}
        </a>
    </div>
    <div>
        @if($button2 == '경락 확인서')
        <a href="#" class="btn btn-outline-primary w-100 py-2 fw-semibold" type="button"
        @click="
            Alpine.store(`modal`).showHtmlFromUrl(`/v2/components/modals/auctionConfirmationDoc`, 
            {title: `경락 확인서`, size: `modal-xl modal-dialog-centered`, footerButtons: [{text: `닫기`, class: `btn-secondary`, dismiss: true}]},
            {
                content: {}
            }
        )"
        >
            {{ $button2 }}
        </a>
        @else
        <a href="{{ $button2Link }}" class="btn btn-outline-primary w-100 py-2 fw-semibold" type="button">
            {{ $button2 }}
        </a>
        @endif
    </div>
</div>