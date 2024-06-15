<script setup>
import { ref, onMounted, watch, defineEmits } from 'vue';
import api from '../../../api';

const props = defineProps({
  house: {
    type: Object,
    required: true
  }
});

const emit = defineEmits(['update']);

const residentialStatuses = ref([]);
const residentHouses = ref([]);
const residents = ref([]);

const selectResidentialStatus = ref('');
const selectResident = ref('');

const fetchResidentialStatus = async () => {
  await api.get(`/api/residential-statuses`)
    .then(response => {
      residentialStatuses.value = response.data.data;
      selectResidentialStatus.value = response.data.data[0].id;
    })
    .catch((error) => {
      toastr.error(error.response.data.message ?? 'Maaf terjadi kesalahan');
    });;
};

const fetchResidents = async () => {
  await api.get(`/api/residents`)
    .then(response => {
      residents.value = response.data.data.filter(resident => !resident.is_resident);
    })
    .catch((error) => {
      toastr.error(error.response.data.message ?? 'Maaf terjadi kesalahan');
    });;
}

watch(
  () => props.house,
  (newHouse) => {
    residentHouses.value = newHouse.resident_houses;
    fetchResidents();
  },
  { immediate: true },
);

onMounted(() => {
  fetchResidentialStatus();
  fetchResidents();
});

const addResidentHouse = async () => {
  let formData = new FormData();

  formData.append('house_id', props.house.id);
  formData.append('resident_id', selectResident.value);
  formData.append('residential_status_id', selectResidentialStatus.value);

  await api.post(`/api/resident-house/`, formData)
    .then((response) => {
      emit('update');
      toastr.success(response.data.message);
    })
    .catch((error) => {
      toastr.error(error.response.data.message);
    });
};

const deleteResidentHouse = async (id) => {
  let formData = new FormData();

  formData.append('id', id);

  await api.delete(`/api/resident-house/${id}`, formData)
    .then((response) => {
      emit('update');
      toastr.success(response.data.message);
    })
    .catch((error) => {
      toastr.error(error.response.data.message);
    });
};
</script>

<template>
  <div class="col-md-6">
    <div class="card card-success">
      <div class="card-header">
        <h3 class="card-title">Penghuni</h3>
      </div>

      <div class="card-body">
        <div class="form-group">
          <label for="residentialStatus">Jenis</label>
          <select class="custom-select" id="residentialStatus" v-model="selectResidentialStatus">
            <option v-for="residentialStatus in residentialStatuses" :value="residentialStatus.id">
              {{ residentialStatus.name }}
            </option>
          </select>
        </div>

        <form @submit.prevent="addResidentHouse()">
          <div class="form-group">
            <label for="resident">Tambah Penghuni</label>
            <div class="row">
              <div class="col-md-11">
                <select class="custom-select" id="resident" v-model="selectResident">
                  <option v-for="resident in residents" :value="resident.id">
                    {{ resident.name }} - {{ resident.id_card_number }}
                  </option>
                </select>
              </div>

              <div class="col-md-1">
                <button type="submit" class="btn btn-primary">
                  <i class="fas fa-plus-circle"></i>
                </button>
              </div>
            </div>
          </div>
        </form>

        <div class="col-12">
          <div class="row">
            <label>Daftar Penghuni</label>
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th class="text-center">Nama</th>
                  <th class="text-center">Nomor Identitas</th>
                  <th class="text-center">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(residentHouse, index) in residentHouses" :key="index">
                  <td v-if="residentHouse.deleted_at == null">{{ residentHouse.resident.name }}</td>
                  <td v-if="residentHouse.deleted_at == null">{{ residentHouse.resident.id_card_number }}</td>
                  <td v-if="residentHouse.deleted_at == null" class="text-center">
                    <form @submit.prevent="deleteResidentHouse(residentHouse.id)">
                      <button type="submit" class="btn btn-xs btn-danger">
                        <i class="fas fa-sign-out-alt"></i> Keluar
                      </button>
                    </form>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>