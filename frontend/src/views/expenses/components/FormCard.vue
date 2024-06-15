<script setup>
import { ref, defineEmits } from 'vue';
import api from '../../../api';

const description = ref('');
const amount = ref('');
const date = ref('')

const emit = defineEmits(['update']);

const handleSubmit = async () => {
  let formData = new FormData();

  formData.append('description', description.value);
  formData.append('amount', amount.value);
  formData.append('date', date.value);

  await api.post(`/api/expenses/`, formData)
    .then((response) => {
      emit('update');
      description.value = '';
      amount.value = '';
      date.value = '';
      toastr.success(response.data.message);
    })
    .catch((error) => {
      toastr.error(error.response.data.message);
    });
};
</script>

<template>
  <div class="col-md-6">
    <div class="card card-danger">
      <div class="card-header">
        <h3 class="card-title">Form Pengeluaran</h3>
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
            <input type="date" class="form-control" id="date" v-model="date">
          </div>
        </div>

        <div class="card-footer">
          <button type="submit" class="btn btn-danger">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</template>