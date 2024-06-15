<script setup>
import { ref, onMounted } from 'vue';
import api from '../../api';
import HouseCard from './components/HouseCard.vue';

const houses = ref([]);

const fetchDataHouses = async () => {
  await api.get('/api/houses')
    .then(response => {
      houses.value = response.data.data;
    })
    .catch((error) => {
      toastr.error(error.response.data.message ?? 'Maaf terjadi kesalahan');
    });
}

onMounted(() => {
  fetchDataHouses();
});
</script>

<template>
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Data Hunian</h1>
        </div>
        <div class="col-sm-6">
          <RouterLink :to="{ name: 'houses.create' }" class="btn btn-primary float-right">
            <i class="fas fa-plus-circle mr-2"></i>
            Tambah Data Baru
          </RouterLink>
        </div>
      </div>
    </div>
  </section>

  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div v-for="(house, index) in houses" :key="index" class="col-md-3">
          <HouseCard :house="house"></HouseCard>
        </div>
      </div>
    </div>
  </section>
</template>