<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import api from '../../api';
import EditForm from './components/EditForm.vue';
import ResidentForm from './components/ResidentForm.vue';
import HistoryCard from './components/HistoryCard.vue';

const route = useRoute();

const house = ref({});

const fetchDataHouse = async () => {
  await api.get(`/api/houses/${route.params.id}`)
    .then(response => {
      house.value = response.data.data;
    })
    .catch((error) => {
      toastr.error(error.response.data.message ?? 'Maaf terjadi kesalahan');
    });
};

onMounted(() => {
  fetchDataHouse();

  $('.custom-file-input').on('change', () => {
    const fileName = $('#houseImage').val().split('\\').pop();
    $('#houseImage').next('.custom-file-label').html(fileName);
  });
});

const handleUpdateSuccess = () => {
  fetchDataHouse();
}
</script>

<template>
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Data Hunian</h1>
        </div>
      </div>
    </div>
  </section>

  <section class="content">
    <div class="col-12">
      <div class="row">
        <EditForm :house="house" />
        <ResidentForm :house="house" @update="handleUpdateSuccess()" />
      </div>
    </div>

    <div class="col-12">
      <div class="row">
        <HistoryCard :house="house" />
      </div>
    </div>
  </section>
</template>