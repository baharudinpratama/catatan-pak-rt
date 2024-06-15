<script setup>
import { ref } from 'vue';
import moment from 'moment';
import api from './../api';
import BarChart from './components/BarChart.vue';
import PieChart from './components/PieChart.vue';

const currentMonth = moment().format('M');
const selectedMonth = ref(currentMonth);
const months = ref([
  { value: 1, text: 'Januari' },
  { value: 2, text: 'Februari' },
  { value: 3, text: 'Maret' },
  { value: 4, text: 'April' },
  { value: 5, text: 'Mei' },
  { value: 6, text: 'Juni' },
  { value: 7, text: 'Juli' },
  { value: 8, text: 'Agustus' },
  { value: 9, text: 'September' },
  { value: 10, text: 'Oktober' },
  { value: 11, text: 'November' },
  { value: 12, text: 'Desember' },
]);

const downloadReport = async (reportId) => {
  let url = `/api/report/${reportId}`;
  let fileName = 'rekap-per-bulan';

  if (reportId == 2) {
    url = `/api/report/${reportId}/${selectedMonth.value}`;
    fileName = 'transaksi-per-bulan';
  }

  await api.get(url, { responseType: 'blob' })
    .then(response => {
      const url = window.URL.createObjectURL(new Blob([response.data]));

      const link = document.createElement('a');
      link.href = url;
      link.setAttribute('download', `${fileName}.pdf`);

      document.body.appendChild(link);

      link.click();

      document.body.removeChild(link);

      month.value = null;
    })
    .catch(error => {
      console.log(error);
    });
}
</script>

<template>
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Dashboard</h1>
        </div>
      </div>
    </div>
  </section>

  <section class="content">
    <div class="container-fluid">

      <div class="row mt-2">
        <div class="col-md-12">
          <div class="card card-success">
            <div class="card-header">
              <div class="card-title">
                Laporan
              </div>
            </div>

            <div class="card-body">
              <div class="row">
                <div class="col-md-6 col-12">
                  <div class="info-box">
                    <span class="info-box-icon bg-secondary" @click="downloadReport(1)">
                      <i class="fas fa-download"></i>
                    </span>
                    <div class="info-box-content">
                      <span class="info-box-text">Download Rekap Pendapatan dan Pengeluaran</span>
                    </div>
                  </div>
                </div>

                <div class="col-md-6 col-12">
                  <div class="info-box">
                    <span class="info-box-icon bg-secondary" @click="downloadReport(2)">
                      <i class="fas fa-download"></i>
                    </span>
                    <div class="info-box-content">
                      <span class="info-box-text">Download Transaksi per Bulan</span>
                      <span>
                        <select class="form-control" v-model="selectedMonth">
                          <option v-for="month in months" :key="month.value" :value="month.value">
                            {{ month.text }}
                          </option>
                        </select>
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row mt-2">
        <div class="col-md-6">
          <BarChart />
        </div>

        <div class="col-md-6">
          <PieChart />
        </div>
      </div>
    </div>
  </section>
</template>