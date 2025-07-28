// 전역 공유 함수
window.showShareModal = function() {
    if (typeof Alpine !== 'undefined' && Alpine.store('modal')) {
        Alpine.store('modal').showHtmlFromUrl('/v2/components/share-modal', {
            title: '공유하기',
            size: 'modal-dialog-centered',
            showFooter: false,
            id: 'shareModal'
        });
    } else {
        console.error('Alpine.js 또는 modal store가 로드되지 않았습니다.');
    }
};

// QR 코드 생성을 위한 간단한 구현
window.QRCode = function(element, options) {
    // QR 코드 라이브러리가 로드되지 않은 경우의 폴백
    if (typeof qrcode === 'undefined') {
        element.innerHTML = `
            <div class="text-center p-4 border rounded">
                <i class="bi bi-qr-code fs-1 text-muted"></i>
                <br>
                <small class="text-muted">QR 코드 라이브러리를 로딩 중...</small>
            </div>
        `;
        return;
    }
    
    try {
        const qr = qrcode(0, 'M');
        qr.addData(options.text);
        qr.make();
        
        element.innerHTML = qr.createImgTag(4, 8);
    } catch (error) {
        console.error('QR 코드 생성 실패:', error);
        element.innerHTML = `
            <div class="text-center p-4 border rounded">
                <i class="bi bi-exclamation-circle fs-1 text-warning"></i>
                <br>
                <small class="text-muted">QR 코드 생성에 실패했습니다.</small>
            </div>
        `;
    }
};