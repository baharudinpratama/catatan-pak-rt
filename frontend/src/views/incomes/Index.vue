<script setup>
import { ref } from 'vue';
import api from '../../api';
import HistoryCard from './components/HistoryCard.vue';

const update = ref(0);

const generatePayment = async () => {
  await api.post('/api/maintenance-payments')
    .then(response => {
      update.value++;
      toastr.success(response.data.message);
    })
    .catch((error) => {
      toastr.error(error.response.data.message ?? 'Maaf terjadi kesalahan');
    });
}
</script>

<template>
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Pendapatan</h1>
        </div>
        <div class="col-sm-6">
          <button class="btn btn-primary float-right" @click="generatePayment">
            <i class="fas fa-plus-circle mr-2"></i>
            Tambah Tagihan Bulan Ini
          </button>
        </div>
      </div>
    </div>
  </section>

  <section class="content">
    <div class="col-12">
      <div class="row">
        <HistoryCard :update="update"/>
      </div>
    </div>
  </section>
</template>