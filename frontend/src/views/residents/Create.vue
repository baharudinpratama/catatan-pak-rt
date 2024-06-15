<script setup>
import { ref, onMounted, watch } from 'vue';
import { useRouter } from 'vue-router';
import api from "../../api";

const router = useRouter();

const name = ref('');
const idCardNumber = ref('');
const idCardImage = ref('');
const phoneNumber = ref('');
const maritalStatus = ref('0');
const active = ref('1');

const handleFileChange = (e) => {
  idCardImage.value = e.target.files[0];
}

const handleStore = async () => {
  let formData = new FormData();

  formData.append('name', name.value);
  formData.append('id_card_number', idCardNumber.value);
  formData.append('id_card_image', idCardImage.value);
  formData.append('phone_number', phoneNumber.value);
  formData.append('marital_status', maritalStatus.value);
  formData.append('active', active.value);

  await api.post('/api/residents', formData)
    .then(response => {
      toastr.success(response.data.message);
      router.push({ path: '/residents' });
    })
    .catch(error => {
      toastr.error(error.response.data.message);
    });
};

onMounted(() => {
  $('.custom-file-input').on('change', () => {
    const fileName = $('#idCardImage').val().split('\\').pop();
    $('#idCardImage').next('.custom-file-label').html(fileName);
  });
});
</script>

<template>
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Form Penghuni</h1>
        </div>
      </div>
    </div>
  </section>

  <section class="content">
    <div class="col-md-6">
      <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title">Tambah Data</h3>
        </div>

        <form @submit.prevent="handleStore()">
          <div class="card-body">
            <div class="form-group">
              <label for="name">Nama</label>
              <input type="text" class="form-control" id="name" v-model="name">
            </div>

            <div class="form-group">
              <label for="id-card-number">Nomor Identitas</label>
              <input type="text" class="form-control" id="id-card-number" v-model="idCardNumber">
            </div>

            <div class="form-group">
              <label for="exampleInputFile">Foto Kartu Identitas</label>
              <div class="input-group">
                <div class="custom-file">
                  <input type="file" class="custom-file-input" id="idCardImage" @change="handleFileChange($event)">
                  <label class="custom-file-label" for="idCardImage">Pilih gambar</label>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label for="phoneNumber">Nomor HP</label>
              <input type="text" class="form-control" id="phoneNumber" v-model="phoneNumber">
            </div>

            <div class="form-group">
              <label for="active">Status Pernikahan</label>
              <select class="custom-select" id="active" v-model="maritalStatus">
                <option value="1">Terikat Pernikahan</option>
                <option value="0">Tidak Terikat Pernikahan</option>
              </select>
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
  </section>
</template>