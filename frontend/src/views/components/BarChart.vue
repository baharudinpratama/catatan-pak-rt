<script setup>
import { ref, onMounted } from 'vue';
import api from '../../api';

const fetchDataReport = async () => {
  await api.get('/api/annual-report')
    .then(response => {
      const monthlyIncomes = response.data.data.monthly_incomes.map(item => item.total);
      const monthlyExpenses = response.data.data.monthly_expenses.map(item => item.total);
      setChart(monthlyIncomes, monthlyExpenses);
    })
    .catch(error => {
      toastr.fire(error.response.data.message ?? 'Maaf terjadi kesalahan');
    });
}

const setChart = (incomes, expenses) => {
  let barChartCanvas = $('#barChart').get(0).getContext('2d');

  let barChartData = {
    labels: [
      'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli',
      'Agustus', 'September', 'Oktober', 'November', 'Desember',
    ],
    datasets: [
      {
        label: 'Pendapatan',
        backgroundColor: '#2f4b7c',
        borderColor: 'rgba(60,141,188,0.8)',
        data: incomes,
      },
      {
        label: 'Pengeluaran',
        backgroundColor: '#ff7c43',
        borderColor: 'rgba(210, 214, 222, 1)',
        data: expenses,
      },
    ]
  }

  let barChartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    datasetFill: false,
  }

  new Chart(barChartCanvas, {
    type: 'bar',
    data: barChartData,
    options: barChartOptions,
  });
};

onMounted(() => {
  fetchDataReport();
});
</script>

<template>
  <div class="card card-success">
    <div class="card-header">
      <h3 class="card-title">Rangkuman Transaksi per Bulan</h3>
      <div class="card-tools">
        <button type="button" class="btn btn-tool" data-card-widget="collapse">
          <i class="fas fa-minus"></i>
        </button>
      </div>
    </div>

    <div class="card-body">
      <div class="chart">
        <canvas id="barChart" style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
      </div>
    </div>
  </div>
</template>