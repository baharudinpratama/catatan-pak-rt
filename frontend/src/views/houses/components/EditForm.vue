<script setup>
import { ref, onMounted, watch } from 'vue';
import { useRouter } from 'vue-router';
import api from '../../../api';

const props = defineProps({
  house: {
    type: Object,
    required: true
  }
});

const router = useRouter();

const houseNumber = ref('');
const description = ref('');
const houseImage = ref('');
const active = ref('');

watch(
  () => props.house,
  (newHouse) => {
    houseNumber.value = newHouse.house_number;
    description.value = newHouse.description;
    active.value = newHouse.active;
  },
  { immediate: true },
);

onMounted(() => {
  $('.custom-file-input').on('change', () => {
    const fileName = $('#houseImage').val().split('\\').pop();
    $('#houseImage').next('.custom-file-label').html(fileName);
  });
});

const handleFileChange = (e) => {
  houseImage.value = e.target.files[0];
};

const handleUpdate = async () => {
  let formData = new FormData();

  formData.append('house_number', houseNumber.value);
  if (houseImage.value !== '') {
    formData.append('house_image', houseImage.value);
  }
  formData.append('description', description.value);
  formData.append('active', active.value);
  formData.append('_method', 'PUT');

  await api.post(`/api/houses/${props.house.id}`, formData)
    .then((response) => {
      toastr.success(response.data.message);
      router.push({ path: '/houses' });
    })
    .catch((error) => {
      toastr.error(error.response.data.message);
    });
};
</script>

<template>
  <div class="col-md-6">
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">Edit Data</h3>
      </div>

      <form @submit.prevent="handleUpdate()">
        <div class="card-body">
          <div class="form-group">
            <label for="houseNumber">Nomor Rumah</label>
            <input type="text" class="form-control" id="houseNumber" v-model="houseNumber">
          </div>

          <div class="form-group">
            <label for="description">Deskripsi</label>
            <input type="text" class="form-control" id="description" v-model="description">
          </div>

          <div class="form-group">
            <label for="exampleInputFile">Foto Rumah</label>
            <div class="input-group">
              <div class="custom-file">
                <input type="file" class="custom-file-input" id="houseImage" @change="handleFileChange">
                <label class="custom-file-label" for="houseImage">Pilih file</label>
              </div>
            </div>
          </div>

          <!-- <div class="form-group">
            <label for="active">Status</label>
            <select class="custom-select" id="active" v-model="active">
              <option value="1">Aktif</option>
              <option value="0">Non-aktif</option>
            </select>
          </div> -->
        </div>

        <div class="card-footer">
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</template>