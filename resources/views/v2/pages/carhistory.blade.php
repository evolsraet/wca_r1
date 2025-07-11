<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <meta name="format-detection" content="telephone=no">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="default">
  <title>차량 이력 조회</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: #f8f9fa; }
    .card { max-width: 700px; margin: 50px auto; box-shadow: 0 4px 10px rgba(0,0,0,0.1); border-radius: 1rem; }
    .card-header { background-color: #0d6efd; color: white; border-top-left-radius: 1rem; border-top-right-radius: 1rem; }

    /* 모바일 반응형 스타일 */
    @media (max-width: 768px) {
      .card {
        margin: 20px auto;
        max-width: 95%;
        border-radius: 0.5rem;
      }
      .card-header {
        border-top-left-radius: 0.5rem;
        border-top-right-radius: 0.5rem;
      }
      .table-responsive {
        font-size: 0.875rem;
      }
      .table th, .table td {
        padding: 0.5rem 0.25rem;
      }
      .btn {
        padding: 0.75rem 1rem;
        font-size: 1rem;
      }
      .form-control {
        font-size: 1rem;
        padding: 0.75rem;
      }
    }
  </style>
</head>
<body>
<div class="container">
  <div class="card">
    <div class="card-header text-center py-3">
      <h4 class="mb-0">차량 이력 조회</h4>
    </div>
    <div class="card-body">
      {{-- <div class="mb-3">
        <label for="carNo" class="form-label">차량번호</label>
        <input type="text" class="form-control" id="carNo" placeholder="예: 08오5060">
      </div> --}}
      <input type="hidden" class="form-control" id="carNo" value="<?=$_GET['car_no']?>" placeholder="예: 08오5060">

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
        <div class="table-responsive">
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
</div>

<script>
// 상수 정의
const CONSTANTS = {
  API_ENDPOINTS: {
    CAR_HISTORY: 'carHistory',
    CAR_HISTORY_CRASH: 'carHistoryCrash'
  },
  DATE_FIELDS: ['r001', 'r003', 'r105', 'r202-02', 'r205-02', 'r203', 'r502-02'],
  CURRENCY_FIELDS: ['r402', 'r404', 'r502-03', 'r502-06', 'r502-07', 'r502-08', 'r502-15'],
  MAPPINGS: {
    USAGE: {
      '1': '관용',
      '2': '자가용',
      '3': '영업용',
      '4': '택시'
    },
    VEHICLE_TYPE: {
      '1': '승용',
      '2': '승합',
      '3': '화물',
      '4': '특수'
    },
    CHANGE_TYPE: {
      '01': '최초등록',
      '02': '차량번호 변경',
      '04': '소유자 변경'
    },
    ACCIDENT_TYPE: {
      '1': '내차보험으로 처리한 내차 사고',
      '2': '타차보험으로 처리된 내차 사고',
      '3': '대물가해'
    }
  }
};

// 코드 매핑
const CODE_MAP = {
  r000: "결과코드", r001: "조회일자", r002: "차량번호", r003: "기준일자", r004: "차량연식", r005: "차명",
  r101: "제조사, 제조국", r102: "차종", r103: "용도", r104: "배기량", r105: "최초보험가입일", r106: "사용연료",
  r107: "차체형상", r108: "차량옵션", r109: "색상", r111: "제조사(영문)", r112: "세부모델", r301: "관용이력",
  r302: "영업용(일반)이력", r303: "영업용(대여)이력", r401: "자차피해사고횟수", r402: "자차피해보험금합",
  r403: "타차가해사고횟수", r404: "타차가해보험금합", r405: "일반전손사고건수", r406_01: "일반전손사고일자",
  r407: "침수전/분손사고건수", r408_01: "침수전/분손사고일자", r409: "도난전손사고건수",
  r410_01: "도난전손사고일자", r501: "사고건수", r510: "자차미가입기간횟수", r511_01: "미가입기간",
  r701: "차량가격(범위)", r601: "주행거리정보유무", r201: "차량정보 변경건수", r204: "소유자 변경 횟수"
};

// 서브 테이블 설정
const SUB_TABLE_CONFIGS = {
  r202: {
    title: '차량번호 변경이력',
    columns: {
      'r202-02': '변경일자',
      'r202-01': '구분',
      'r202-03': '변경차량번호',
      'r202-05': '차종',
      'r202-04': '용도'
    }
  },
  r205: {
    title: '소유자 변경이력',
    columns: {
      'r205-02': '변경일자',
      'r205-01': '구분',
      'r202-05': '차종',
      'r202-04': '용도'
    }
  },
  r203: {
    title: '최초등록 정보',
    columns: {
      'r203': '등록일자',
      'r202-01': '구분',
      'r202-03': '차량번호',
      'r202-05': '차종',
      'r202-04': '용도'
    }
  },
  r502: {
    title: '사고 이력',
    columns: {
      'r502-01': '사고구분',
      'r502-02': '사고일자',
      'r502-03': '보험금',
      'r502-06': '부품',
      'r502-07': '공임',
      'r502-08': '도장',
      'r502-15': '수리비'
    }
  },
  r602: {
    title: '주행거리 이력',
    columns: {
      'r602-01': '수집일',
      'r602-03': '주행거리',
      'r602-02': '제공처'
    }
  }
};

// 유틸리티 함수들
const Utils = {
  formatDate: (dateString) => {
    if (!dateString) return '';
    return dateString.replace(/(\d{4})(\d{2})(\d{2})/, '$1-$2-$3');
  },

  formatCurrency: (value) => {
    if (!value) return '';
    return Number(value).toLocaleString();
  },

  getMappedValue: (value, mappingType) => {
    return CONSTANTS.MAPPINGS[mappingType][value] || value;
  },

  getCodeLabel: (key) => {
    return CODE_MAP[key.replace(/-/g, '_')] || key;
  }
};

// 데이터 처리 클래스
class DataProcessor {
  static processMainData(data) {
    const processedData = {};

    for (const [key, value] of Object.entries(data)) {
      if (typeof value === 'object') continue;

      let processedValue = value;

      // 날짜 형식 변환
      if (CONSTANTS.DATE_FIELDS.includes(key)) {
        processedValue = Utils.formatDate(value);
      }

      // 통화 형식 변환
      if (CONSTANTS.CURRENCY_FIELDS.includes(key)) {
        processedValue = Utils.formatCurrency(value);
      }

      // 용도 매핑
      if (key === 'r103') {
        processedValue = Utils.getMappedValue(value, 'USAGE');
      }

      // 차종 매핑
      if (key === 'r102') {
        processedValue = Utils.getMappedValue(value, 'VEHICLE_TYPE');
      }

      processedData[key] = processedValue;
    }

    return processedData;
  }

  static processSubTableData(row, columnKey) {
    let value = row[columnKey] ?? '';

    // 날짜 형식 변환
    if (CONSTANTS.DATE_FIELDS.includes(columnKey)) {
      value = Utils.formatDate(value);
    }

    // 통화 형식 변환
    if (CONSTANTS.CURRENCY_FIELDS.includes(columnKey)) {
      value = Utils.formatCurrency(value);
    }

    // 변경 구분 매핑
    if (columnKey === 'r202-01' || columnKey === 'r205-01') {
      value = Utils.getMappedValue(value, 'CHANGE_TYPE');
    }

    // 차종 매핑
    if (columnKey === 'r202-05') {
      value = Utils.getMappedValue(value, 'VEHICLE_TYPE');
    }

    // 용도 매핑
    if (columnKey === 'r202-04') {
      value = Utils.getMappedValue(value, 'USAGE');
    }

    // 사고 구분 매핑
    if (columnKey === 'r502-01') {
      value = Utils.getMappedValue(value, 'ACCIDENT_TYPE');
    }

    return value;
  }
}

// UI 렌더링 클래스
class UIRenderer {
  static showLoading(resultArea) {
    resultArea.innerHTML = `
      <h5 class="mb-3">요약 정보</h5>
      <div class="table-responsive">
        <table class="table table-bordered table-striped" id="resultTable">
          <thead class="table-light">
            <tr><th>항목</th><th>값</th></tr>
          </thead>
          <tbody><tr><td colspan="2" class="text-center">조회 중입니다...</td></tr></tbody>
        </table>
      </div>
    `;
    resultArea.style.display = 'none';
  }

  static showError(resultArea, error) {
    const tableBody = resultArea.querySelector('#resultTable tbody');
    tableBody.innerHTML = `<tr><td colspan="2" class="text-danger text-center">에러: ${error.message}</td></tr>`;
    resultArea.style.display = 'block';
  }

  static renderMainTable(data, resultArea) {
    const tableBody = resultArea.querySelector('#resultTable tbody');
    tableBody.innerHTML = '';

    const processedData = DataProcessor.processMainData(data);

    for (const [key, value] of Object.entries(processedData)) {
      const label = Utils.getCodeLabel(key);
      tableBody.innerHTML += `<tr><td><strong>${label}</strong></td><td>${value}</td></tr>`;
    }

    resultArea.style.display = 'block';
  }

  static renderSubTables(data, resultArea) {
    Object.entries(SUB_TABLE_CONFIGS).forEach(([key, config]) => {
      const items = data[key];
      if (!Array.isArray(items) || items.length === 0) return;

      const section = document.createElement('div');
      section.classList.add('mt-5');

      let tableHTML = `<h5 class="mb-3">${config.title}</h5><div class="table-responsive"><table class="table table-bordered"><thead><tr>`;

      // 헤더 생성
      for (const col in config.columns) {
        tableHTML += `<th>${config.columns[col]}</th>`;
      }
      tableHTML += `</tr></thead><tbody>`;

      // 데이터 행 생성
      items.forEach(row => {
        tableHTML += `<tr>`;
        for (const col in config.columns) {
          const value = DataProcessor.processSubTableData(row, col);
          tableHTML += `<td>${value}</td>`;
        }
        tableHTML += `</tr>`;
      });

      tableHTML += `</tbody></table></div>`;
      section.innerHTML = tableHTML;
      resultArea.appendChild(section);
    });
  }

  static renderCrashData(data, container) {
    container.innerHTML = '';
    container.style.display = 'block';

    const crashTypes = {
      self: '자차 피해 사고',
      other: '타차 가해 사고'
    };

    for (const [type, title] of Object.entries(crashTypes)) {
      const records = data[type];
      if (!Array.isArray(records) || records.length === 0) continue;

      const section = document.createElement('div');
      section.classList.add('mt-5');

      let tableHTML = `<h5 class="mb-3">${title}</h5>`;
      tableHTML += `<div class="table-responsive"><table class="table table-bordered"><thead><tr><th>사고일자</th><th>부품</th><th>공임</th><th>도장</th><th>총 수리비</th></tr></thead><tbody>`;

      records.forEach(row => {
        tableHTML += `<tr><td>${row.crashDate}</td><td>${row.part}</td><td>${row.labor}</td><td>${row.paint}</td><td>${row.cost}</td></tr>`;
      });

      tableHTML += `</tbody></table></div>`;
      section.innerHTML = tableHTML;
      container.appendChild(section);
    }
  }
}

// 메인 함수
async function fetchCarHistory() {
  const carNo = document.getElementById('carNo').value.trim();
  const apiType = document.querySelector('input[name="apiType"]:checked').value;
  const resultArea = document.getElementById('resultArea');

  if (!carNo) {
    alert('차량번호를 입력해주세요.');
    return;
  }

  UIRenderer.showLoading(resultArea);

  try {
    const endpoint = `/api/${apiType}?car_no=${encodeURIComponent(carNo)}`;
    const response = await fetch(endpoint);

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    const json = await response.json();
    const data = json.data;

    if (apiType === CONSTANTS.API_ENDPOINTS.CAR_HISTORY_CRASH) {
      UIRenderer.renderCrashData(data, resultArea);
    } else {
      UIRenderer.renderMainTable(data, resultArea);
      UIRenderer.renderSubTables(data, resultArea);
    }
  } catch (error) {
    console.error('차량 이력 조회 중 오류 발생:', error);
    UIRenderer.showError(resultArea, error);
  }
}
</script>
</body>
</html>
