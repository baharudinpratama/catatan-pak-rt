<script setup>
import { ref, onMounted } from 'vue';
import api from '../../api';

const fetchDataReport = async () => {
  await api.get('/api/annual-report')
    .then(response => {
      const yearlyIncome = response.data.data.yearly_income;
      const yearlyExpense = response.data.data.yearly_expense;
      setChart(yearlyIncome, yearlyExpense);
    })
    .catch(error => {
      toastr.fire(error.response.data.message ?? 'Maaf terjadi kesalahan');
    });
}

const setChart = (income, expense) => {
  let pieChartCanvas = $('#pieChart').get(0).getContext('2d');

  let pieChartData = {
    labels: [
      'Pengeluaran',
      'Pendapatan',
    ],
    datasets: [
      {
        data: [expense, income],
        backgroundColor: ['#ffa600', '#003f5c'],
      }
    ],
  }

  let pieChartOptions = {
    maintainAspectRatio: false,
    responsive: true,
  }

  new Chart(pieChartCanvas, {
    type: 'pie',
    data: pieChartData,
    options: pieChartOptions
  })
};

onMounted(() => {
  fetchDataReport();
});
</script>

<template>
  <div class="card card-success">
    <div class="card-header">
      <h3 class="card-title">Rangkuman Transaksi Setahun</h3>
      <div class="card-tools">
        <button type="button" class="btn btn-tool" data-card-widget="collapse">
          <i class="fas fa-minus"></i>
        </button>
      </div>
    </div>

    <div class="card-body">
      <div class="chart">
        <canvas id="pieChart" style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
      </div>
    </div>
  </div>
</template>