
<div class="container my-5">
    <h2 class="mb-4">차량 이력 조회</h2>
    <div class="mb-3">
        <label for="carNo" class="form-label">차량번호</label>
        <input type="text" class="form-control" id="carNo" placeholder="예: 08조5060">
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
    if (!carNo) {
        alert('차량번호를 입력해주세요.');
        return;
    }

    const resultBox = document.getElementById('resultBox');
    resultBox.textContent = '조회 중입니다...';

    fetch(`https://dev.wecar.auction/api/carHistory?car_no=${encodeURIComponent(carNo)}`)
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

