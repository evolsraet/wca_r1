<div x-data="shareModal" class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">
            <i class="bi bi-share me-2"></i>공유하기
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="mb-4">
            <h6 class="fw-bold mb-3">현재 페이지 정보</h6>
            <div class="p-3 bg-light rounded">
                <div class="d-flex align-items-center mb-2">
                    <i class="bi bi-globe me-2 text-primary"></i>
                    <strong x-text="pageInfo.title"></strong>
                </div>
                <div class="d-flex align-items-center text-muted">
                    <i class="bi bi-link-45deg me-2"></i>
                    <small x-text="pageInfo.url" class="text-break"></small>
                </div>
            </div>
        </div>
        {{-- 
        <div class="mb-4">
            <h6 class="fw-bold mb-3">SNS 공유</h6>
            <div class="row g-2">
                <div class="col-3">
                    <button @click="shareToKakao()" class="btn btn-warning w-100 d-flex flex-column align-items-center p-3">
                        <i class="bi bi-chat-fill fs-4 mb-1"></i>
                        <small>카카오톡</small>
                    </button>
                </div>
                <div class="col-3">
                    <button @click="shareToFacebook()" class="btn btn-primary w-100 d-flex flex-column align-items-center p-3">
                        <i class="bi bi-facebook fs-4 mb-1"></i>
                        <small>페이스북</small>
                    </button>
                </div>
                <div class="col-3">
                    <button @click="shareToTwitter()" class="btn btn-info w-100 d-flex flex-column align-items-center p-3">
                        <i class="bi bi-twitter fs-4 mb-1"></i>
                        <small>트위터</small>
                    </button>
                </div>
                <div class="col-3">
                    <button @click="shareToLine()" class="btn btn-success w-100 d-flex flex-column align-items-center p-3">
                        <i class="bi bi-line fs-4 mb-1"></i>
                        <small>라인</small>
                    </button>
                </div>
            </div>
        </div>
         --}}

        <div class="mb-4">
            <h6 class="fw-bold mb-3">URL 복사</h6>
            <div class="input-group">
                <input type="text" class="form-control" x-model="pageInfo.url" readonly id="shareUrl">
                <button class="btn btn-outline-secondary" type="button" @click="copyToClipboard()">
                    <i class="bi bi-clipboard me-1"></i>복사
                </button>
            </div>
            <div x-show="copySuccess" x-transition class="text-success mt-2">
                <i class="bi bi-check-circle me-1"></i>클립보드에 복사되었습니다!
            </div>
        </div>

        {{-- 
        <!-- 네이티브 Web Share API 지원 시 -->
        <div x-show="supportsNativeShare" class="mb-3">
            <h6 class="fw-bold mb-3">기기 공유</h6>
            <button @click="shareNative()" class="btn btn-secondary w-100">
                <i class="bi bi-share me-2"></i>다른 앱으로 공유
            </button>
        </div>

        <!-- QR 코드 섹션 -->
        <div class="mb-3">
            <h6 class="fw-bold mb-3">QR 코드</h6>
            <div class="text-center">
                <button @click="toggleQR()" class="btn btn-outline-dark">
                    <i class="bi bi-qr-code me-2"></i>QR 코드 생성
                </button>
                <div x-show="showQR" x-transition class="mt-3">
                    <div id="qrcode" class="d-flex justify-content-center"></div>
                    <small class="text-muted">QR 코드를 스캔하여 페이지에 접속하세요</small>
                </div>
            </div>
        </div>
         --}}
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">닫기</button>
    </div>
</div>