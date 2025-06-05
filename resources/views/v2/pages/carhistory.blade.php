<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <title>차량 이력 조회</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: #f8f9fa; }
    .card { max-width: 700px; margin: 50px auto; box-shadow: 0 4px 10px rgba(0,0,0,0.1); border-radius: 1rem; }
    .card-header { background-color: #0d6efd; color: white; border-top-left-radius: 1rem; border-top-right-radius: 1rem; }
  </style>
</head>
<body>
<div class="container">
  <div class="card">
    <div class="card-header text-center py-3">
      <h4 class="mb-0">차량 이력 조회</h4>
    </div>
    <div class="card-body">
      <div class="mb-3">
        <label for="carNo" class="form-label">차량번호</label>
        <input type="text" class="form-control" id="carNo" placeholder="예: 08오5060">
      </div>

      <div class="mb-3">
        <label class="form-label d-block">조회 종류</label>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="apiType" id="history" value="carHistory" checked onchange="fetchCarHistory()">
          <label class="form-check-label" for="history">기본 이력 조회</label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="apiType" id="crash" value="carHistoryCrash" onchange="fetchCarHistory()">
          <label class="form-check-label" for="crash">사고 이력 조회</label>
        </div>
      </div>

      <div class="d-grid">
        <button class="btn btn-primary" onclick="fetchCarHistory()">조회하기</button>
      </div>

      <div class="mt-5" id="resultArea" style="display: none;">
        <h5 class="mb-3">요약 정보</h5>
        <table class="table table-bordered table-striped" id="resultTable">
          <thead class="table-light">
            <tr><th>항목</th><th>값</th></tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
const codeMap = {
  r000: "결과코드", r001: "조회일자", r002: "차량번호", r003: "기준일자", r004: "차량연식", r005: "차명",
  r101: "제조사, 제조국", r102: "차종", r103: "용도", r104: "배기량", r105: "최초보험가입일", r106: "사용연료",
  r107: "차체형상", r108: "차량옵션", r109: "색상", r111: "제조사(영문)", r112: "세부모델", r301: "관용이력",
  r302: "영업용(일반)이력", r303: "영업용(대여)이력", r401: "자차피해사고횟수", r402: "자차피해보험금합",
  r403: "타차가해사고횟수", r404: "타차가해보험금합", r405: "일반전손사고건수", r406_01: "일반전손사고일자",
  r407: "침수전/분손사고건수", r408_01: "침수전/분손사고일자", r409: "도난전손사고건수",
  r410_01: "도난전손사고일자", r501: "사고건수", r510: "자차미가입기간횟수", r511_01: "미가입기간",
  r701: "차량가격(범위)", r601: "주행거리정보유무"
};

const subTableConfigs = {
  r202: { title: '차량번호 변경이력', columns: { 'r202-02': '변경일자', 'r202-01': '구분', 'r202-03': '변경차량번호', 'r202-05': '차종', 'r202-04': '용도' } },
  r205: { title: '소유자 변경이력', columns: { 'r205-02': '변경일자', 'r205-01': '구분', 'r202-05': '차종', 'r202-04': '용도' } },
  r203: { title: '최초등록 정보', columns: { 'r203': '등록일자', 'r202-01': '구분', 'r202-03': '차량번호', 'r202-05': '차종', 'r202-04': '용도' } },
  r502: { title: '사고 이력', columns: { 'r502-01': '사고구분', 'r502-02': '사고일자', 'r502-03': '보험금', 'r502-06': '부품', 'r502-07': '공임', 'r502-08': '도장', 'r502-15': '수리비' } },
  r602: { title: '주행거리 이력', columns: { 'r602-01': '수집일', 'r602-03': '주행거리', 'r602-02': '제공처' } }
};

function fetchCarHistory() {
  const carNo = document.getElementById('carNo').value;
  const apiType = document.querySelector('input[name="apiType"]:checked').value;
  const resultArea = document.getElementById('resultArea');

  resultArea.innerHTML = `
    <h5 class="mb-3">요약 정보</h5>
    <table class="table table-bordered table-striped" id="resultTable">
      <thead class="table-light">
        <tr><th>항목</th><th>값</th></tr>
      </thead>
      <tbody><tr><td colspan="2" class="text-center">조회 중입니다...</td></tr></tbody>
    </table>
  `;
  resultArea.style.display = 'none';

  if (!carNo.trim()) return;

  const endpoint = `https://dev.wecar.auction/api/${apiType}?car_no=${encodeURIComponent(carNo)}`;

  fetch(endpoint)
    .then(res => res.json())
    .then(json => {
      const data = json.data;
      const tableBody = resultArea.querySelector('#resultTable tbody');
      tableBody.innerHTML = '';
      if (apiType === 'carHistoryCrash') {
        renderCrashData(data, resultArea);
      } else {
        for (const key in data) {
          if (typeof data[key] === 'object') continue;
          const label = codeMap[key.replace(/-/g, '_')] || key;
          tableBody.innerHTML += `<tr><td><strong>${label}</strong></td><td>${data[key]}</td></tr>`;
        }
        resultArea.style.display = 'block';
        renderSubTables(data);
      }
    })
    .catch(err => {
      const tableBody = resultArea.querySelector('#resultTable tbody');
      tableBody.innerHTML = `<tr><td colspan="2" class="text-danger text-center">에러: ${err.message}</td></tr>`;
      resultArea.style.display = 'block';
    });
}

function renderSubTables(data) {
  const resultArea = document.getElementById('resultArea');
  Object.entries(subTableConfigs).forEach(([key, config]) => {
    const items = data[key];
    if (!Array.isArray(items)) return;
    const section = document.createElement('div');
    section.classList.add('mt-5');
    let tableHTML = `<h5 class="mb-3">${config.title}</h5><table class="table table-bordered"><thead><tr>`;
    for (const col in config.columns) tableHTML += `<th>${config.columns[col]}</th>`;
    tableHTML += `</tr></thead><tbody>`;
    items.forEach(row => {
      tableHTML += `<tr>`;
      for (const col in config.columns) {
        tableHTML += `<td>${row[col] ?? ''}</td>`;
      }
      tableHTML += `</tr>`;
    });
    tableHTML += `</tbody></table>`;
    section.innerHTML = tableHTML;
    resultArea.appendChild(section);
  });
}

function renderCrashData(data, container) {
  container.innerHTML = '';
  container.style.display = 'block';
  const crashTypes = { self: '자차 피해 사고', other: '타차 가해 사고' };
  for (const type in crashTypes) {
    const records = data[type];
    if (!Array.isArray(records)) continue;
    const section = document.createElement('div');
    section.classList.add('mt-5');
    let tableHTML = `<h5 class="mb-3">${crashTypes[type]}</h5>`;
    tableHTML += `<table class="table table-bordered"><thead><tr><th>사고일자</th><th>부품</th><th>공임</th><th>도장</th><th>총 수리비</th></tr></thead><tbody>`;
    records.forEach(row => {
      tableHTML += `<tr><td>${row.crashDate}</td><td>${row.part}</td><td>${row.labor}</td><td>${row.paint}</td><td>${row.cost}</td></tr>`;
    });
    tableHTML += `</tbody></table>`;
    section.innerHTML = tableHTML;
    container.appendChild(section);
  }
}
</script>
</body>
</html>