<template>
    <div class="container pt-4">
        <div class="col-lg-8 mx-auto py-md-5">
      <p class="fw-bold mb-3">낙찰 이력</p>
      <main>
      <BarChart :chartData="chartData" :options="chartOptions" class="my-4"/>
      </main>
      <p class="fw-bold my-3">차종별 낙찰 이력</p>
      <div class="mb-4 d-sm-flex align-items-sm-center ">
          <p class="refresh-icon" @click="refreshChartData">↻</p>
          <div class="select-container">
          <select class="car-select type-sm ms-3 ">
            <option value="차종 선택">차종 선택</option>
            <option value="기아">기아</option>
            <option value="현대">현대</option>
            <option value="제네시스">제네시스</option>
            <option value="아우디">아우디</option>
            <option value="폭스바겐">폭스바겐</option>
          </select>
        </div>
      </div>
      <main>
        <LineChart :chartData="lineChartData" :options="lineChartOptions" :key="chartKey" class="my-4"/>
      </main>
    </div>
    
</div>
<Footer />
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import { BarChart, LineChart } from 'vue-chart-3';
  import { Chart, registerables } from 'chart.js';
  import Footer from "@/views/layout/footer.vue"
  Chart.register(...registerables);
  
  const chartData = ref({
    labels: ['기아', '현대', '제네시스', '아우디', '폭스바겐'],
    datasets: [
      {
        label: 'Bids Received',
        data: [1000, 1200, 1500, 1100, 1300],
        backgroundColor: function(context) {
          const chart = context.chart;
          const {ctx, chartArea} = chart;
          if (!chartArea) return null;
          return getGradient(ctx, chartArea);
        },
        borderColor: 'rgba(200, 0, 0, 1)',
        borderWidth: 1,
        borderRadius: 20,
        barThickness: 20,
        borderWidth:0
      }
    ]
  });
  // 라인 차트 데이터
const lineChartData = ref({
    labels: chartData.value.labels,
    datasets: [
        {
            label: 'Previous Data',
            data: [900, 1100, 1400, 1000, 1200],
            borderColor: 'grey',
            fill: false,
            tension: 0.1
        },
        {
            label: 'Current Data',
            data: [1000, 1200, 1500, 1100, 1300],
            borderColor: 'red',
            fill: false,
            tension: 0.1,

            pointRadius: [2, 2, 2, 2, 5],
            pointBackgroundColor: ['red', 'red', 'red', 'red', 'white'], 
            pointBorderColor: ['red', 'red', 'red', 'red', 'red'], 
            pointBorderWidth: [1, 1, 1, 1, 3], 
        }
    ]
});
// 차트의 key를 관리할 ref
const chartKey = ref(0);
// 차트 데이터를 새로고침하는 함수
function refreshChartData() {
    chartKey.value++; // key 값을 증가시켜 컴포넌트 재생성 유도
}
const lineChartOptions = ref({
    responsive: true,
    plugins: {
        legend: {
          display: false,
        },
        tooltip: {
            enabled: true,
            backgroundColor: '#fffcee', // 툴팁의 배경색
            bodyColor: '#d8a00e', // 툴팁의 본문 글자색
            titleColor: '#d8a00e', // 툴팁의 타이틀 글자색
            borderColor: '#d8a00e', // 툴팁의 테두리 색
            borderWidth: 1, // 툴팁의 테두리 두께
            bodyFont: {
                weight: 'bold' // 본문 글자 두께
            },
            titleFont: {
                size: 14 // 타이틀 글자 크기
            },
            cornerRadius: 4, // 툴팁 모서리의 둥글기
            displayColors: false, // 색상 상자 표시 여부
            callbacks: {
                title: function(tooltipItems) {
                    return `평균: ${tooltipItems[0].label}`; // 타이틀 변경
                },
                label: function(tooltipItem) {
                    return ` 낙찰액 : ${tooltipItem.raw}만원`;
                },
            }
        },
    },
    scales: {
        y: {
            beginAtZero: true,
            ticks: {
                callback: function(value) {
                    return `${value}만원`;
                }
            }
        },
        x: {
            grid: {
                display: false
            }
        }
    }
});


  const chartOptions = ref({
    responsive: true,
    plugins: {
      legend: {
        display: false,
      },
      tooltip: {
        callbacks: {
          label: function(tooltipItem) {
            return `${tooltipItem.raw}만원`;
          }
        }
      },
    },
    scales: {
      y: {
        beginAtZero: true,
        ticks: {
          callback: function(value) {
            return `${value}만원`;
          }
        }
      },
      x: {
      grid: {
        display: false  // 세로 줄 제거
      }
    }
    },
    plugins: [{
      id: 'valuesOnTopOfBars',
      beforeDraw: function(chart) {
        const ctx = chart.ctx;
        chart.data.datasets.forEach((dataset, i) => {
          ctx.fillStyle = 'black';
          ctx.textAlign = 'center';
          ctx.textBaseline = 'bottom';
          dataset.data.forEach((value, j) => {
            const model = chart.getDatasetMeta(i).data[j]._model;
            ctx.fillText(value, model.x, model.y - 5);
          });
        });
      }
    }]
  });
  
  
// 막대 그래프 그라데이션 생성
  function getGradient(ctx, chartArea) {
    const gradient = ctx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
    gradient.addColorStop(0, 'rgba(255, 0, 0, 0.1)');
    gradient.addColorStop(1, 'rgba(255, 0, 0, 1)');
    return gradient;
  }
  </script>
  
  <style scoped>
  .car-select {
    border-radius: 16px;
    background-color: #fbeaea;
    width: auto;
    color: red;
    border: none;
    cursor: pointer;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    padding-right: 30px; 
}

select {
    height: 40px;
    padding: 0 30px 0 10px;
    border-bottom: 1px solid #041231;
    background: #fff url('../../../../img/icon-red-down-02.png') no-repeat center right 10px;
    cursor: pointer;
}
select.type-sm {
    width: 150px;
}
  </style>
  