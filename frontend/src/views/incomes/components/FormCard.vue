<script setup>
import { ref } from 'vue';
import api from '../../../api';

const description = ref('');
const amount = ref('');

const handleSubmit = async () => {
  let formData = new FormData();

  formData.append('description', description.value);
  formData.append('amount', amount.value);
  formData.append('_method', 'PUT');

  await api.post(`/api/expenses/`, formData)
    .then((response) => {
      toastr.success(response.data.message);
      route.push('/incomes');
    })
    .catch((error) => {
      toastr.error(error.response.data.message);
    });
};
</script>

<template>
  <div class="col-md-12">
    <div class="card card-success">
      <div class="card-header">
        <h3 class="card-title">Form Bayar</h3>
      </div>

      <form id="formExpense" @submit.prevent="handleSubmit()">
        <div class="card-body">
          <div class="form-group">
            <label for="description">Deskripsi</label>
            <input type="text" class="form-control" id="description" v-model="description">
          </div>

          <div class="form-group">
            <label for="amount">Nilai Transaksi</label>
            <input type="number" class="form-control" id="amount" v-model="amount">
          </div>

          <div class="form-group">
            <label for="date">Tanggal Transaksi</label>
            <input type="date" class="form-control" id="date">
          </div>
        </div>

        <div class="card-footer">
          <button type="submit" class="btn btn-success">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</template>