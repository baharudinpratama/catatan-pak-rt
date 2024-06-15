<script setup>
import { ref, watch } from 'vue';
import moment from 'moment';

const props = defineProps({
  house: {
    type: Object,
    required: true
  }
});

const residentHouses = ref([]);

watch(
  () => props.house,
  (newHouse) => {
    residentHouses.value = newHouse.resident_houses;
  },
);
</script>

<template>

  <div class="col-md-12">
    <div class="card card-secondary">
      <div class="card-header">
        <h3 class="card-title">Riwayat Penghuni</h3>
        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse">
            <i class="fas fa-minus"></i>
          </button>
        </div>
      </div>

      <div class="card-body">
        <div class="col-12">
          <div class="row">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th class="text-center">Nama</th>
                  <th class="text-center">Nomor Identitas</th>
                  <th class="text-center">Jenis Hunian</th>
                  <th class="text-center">Tanggal Masuk</th>
                  <th class="text-center">Tanggal Keluar</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(residentHouse, index) in residentHouses" :key="index">
                  <td>{{ residentHouse.resident.name }}</td>
                  <td>{{ residentHouse.resident.id_card_number
                    }}</td>
                  <td>{{ residentHouse.residential_status.name }}</td>
                  <td>{{ moment(residentHouse.created_at).format('DD MMMM YYYY') }}</td>
                  <td>{{
                    residentHouse.deleted_at == null ? '' :
                      moment(residentHouse.deleted_at).format('DD MMMM YYYY')
                  }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>