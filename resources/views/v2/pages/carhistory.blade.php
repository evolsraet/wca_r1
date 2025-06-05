
<div class="container my-5">
    <h2 class="mb-4">차량 이력 조회</h2>

    <div class="mb-3">
        <label for="carNo" class="form-label">차량번호</label>
        <input type="text" class="form-control" id="carNo" placeholder="예: 08조5060">
    </div>

    <div class="mb-3">
        <label for="apiType" class="form-label">조회 종류</label>
        <select class="form-select" id="apiType">
            <option value="carHistory" selected>기본 이력 조회</option>
            <option value="carHistoryCrash">사고 이력 조회</option>
        </select>
    </div>

    <button class="btn btn-primary" onclick="fetchCarHistory()">조회하기</button>

    <div class="mt-4">
        <h5>조회 결과:</h5>
        <pre id="resultBox" class="bg-light p-3 rounded" style="max-height: auto; overflow-y: auto;"></pre>
    </div>
</div>

<script>
function fetchCarHistory() {
    const carNo = document.getElementById('carNo').value;
    const apiType = document.getElementById('apiType').value;
    const resultBox = document.getElementById('resultBox');

    if (!carNo) {
        alert('차량번호를 입력해주세요.');
        return;
    }

    resultBox.textContent = '조회 중입니다...';

    const endpoint = `https://dev.wecar.auction/api/${apiType}?car_no=${encodeURIComponent(carNo)}`;

    fetch(endpoint)
        .then(response => {
            if (!response.ok) throw new Error('조회 실패');
            return response.json();
        })
        .then(data => {
            resultBox.textContent = JSON.stringify(data, null, 2);
        })
        .catch(error => {
            resultBox.textContent = `에러 발생: ${error.message}`;
        });
}
</script>

