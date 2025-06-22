<div x-data="dealerDeliveryConfirm">
    <div class="p-1">
        {{-- 타이틀 --}}
        <div class="text-center mb-3">
            <h5 class="fw-bold">마지막으로 꼼꼼히 확인해 주세요!</h5>
            <div class="text-danger small mt-1">※ 매도용 인감증명서를 준비해주세요</div>
        </div>

        {{-- 정보 테이블 --}}
        <table class="table borderless mb-4">
            <tbody>
                <tr>
                    <th class="text-nowrap text-muted">탁송일</th>
                    <td x-text="auction?.selectedDate ?? '-'"></td>
                </tr>
                <tr>
                    <th class="text-nowrap text-muted">탁송주소</th>
                    <td x-text="'(' + auction?.addr_post + ') ' + auction?.addr1 + ' ' + auction?.addr2"></td>
                </tr>
                <tr>
                    <th class="text-nowrap text-muted">은행</th>
                    <td x-text="'('+auction?.bank + ') ' + auction?.account"></td>
                </tr>
                <tr>
                    <th class="text-nowrap text-muted">고객 연락처</th>
                    <td x-text="auction?.customTel1 + ' ' + auction?.customTel2"></td>
                </tr>
            </tbody>
        </table>

        {{-- 유의사항 --}}
        <div class="text-center text-muted small mb-4">취소와 변경이 어려우니 유의해 주세요.</div>

        {{-- 버튼 영역 --}}
        <div class="d-flex justify-content-between">
            <button type="button" class="btn btn-outline-secondary w-50 me-2" @click="cancel">취소</button>
            <button type="button" class="btn btn-danger w-50" @click="submit">확인</button>
        </div>
    </div>
</div>