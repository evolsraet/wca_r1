<template>
    <div class="container">
        <div class="col-lg-8 mx-auto p-4 py-md-5">
            <div class="proceeding"></div>
      <p class="fw-bold mb-3">낙찰 이력</p>
      <main>
      <BarChart :chartData="chartData" :options="chartOptions" />
      </main>
    </div>
</div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import { BarChart } from 'vue-chart-3';
  import { Chart, registerables } from 'chart.js';
  
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
  
  const chartOptions = ref({
    responsive: true,
    plugins: {
      legend: {
        position: 'top',
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

  </style>
  