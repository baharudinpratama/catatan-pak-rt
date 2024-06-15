<script setup>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import api from '../../api';

const router = useRouter();
const route = useRoute();

const months = Array.from({ length: 12 }, (v, k) => ++k);
const houseNumber = ref('');
const residentName = ref('');
const maintenanceType = ref('');
const fee = ref(0);
const monthsToPay = ref(1);
const totalFee = ref(0);
const paidAt = ref('');

const countFee = () => {
  totalFee.value = parseInt(fee.value) * parseInt(monthsToPay.value);
}

const fetchDataPayment = async () => {
  await api.get(`/api/maintenance-payments/${route.params.id}`)
    .then(response => {
      let payment = response.data.data;

      houseNumber.value = payment.resident_house.house.house_number;
      residentName.value = payment.resident_house.resident.name;
      maintenanceType.value = payment.maintenance_type.name;
      fee.value = payment.maintenance_type.fee;
      countFee();
    })
    .catch((error) => {
      toastr.error(error.response.data.message ?? 'Maaf terjadi kesalahan');
    });
}

const handleUpdate = async () => {
  let formData = new FormData();

  formData.append('months_to_pay', monthsToPay.value);
  formData.append('paid_at', paidAt.value);
  formData.append('_method', 'PUT');

  await api.post(`/api/maintenance-payments/${route.params.id}`, formData)
    .then(response => {
      toastr.success(response.data.message);
      router.push({ path: '/incomes' });
    })
    .catch(error => {
      toastr.error(error.response.data.message);
    });
}

onMounted(() => {
  fetchDataPayment();
});
</script>

<template>
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Pendapatan</h1>
        </div>
      </div>
    </div>
  </section>

  <section class="content">
    <div class="col-12">
      <div class="row">
        <div class="col-md-12">
          <div class="card card-success">
            <div class="card-header">
              <h3 class="card-title">Form Iuran</h3>
            </div>

            <form id="formExpense" @submit.prevent="handleUpdate()">
              <div class="card-body">
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="houseNumber">Nomor Rumah</label>
                      <input type="text" class="form-control" id="houseNumber" v-model="houseNumber" disabled>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="resident">Nama Penghuni</label>
                      <input type="text" class="form-control" id="resident" v-model="residentName" disabled>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="maintenanceType">Tujuan Pembayaran</label>
                      <input type="text" class="form-control" id="maintenanceType" v-model="maintenanceType" disabled>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="fee">Biaya</label>
                      <input type="number" class="form-control" id="fee" v-model="fee" disabled>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="monthsToPay">Banyak Bulan</label>
                      <select class="form-control" id="monthsToPay" v-model="monthsToPay" @change="countFee()">
                        <option v-for="month in months" :key="month" :value="month">
                          {{ month }} bulan
                        </option>
                      </select>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="totalFee">Total Biaya</label>
                      <input type="number" class="form-control" id="totalFee" v-model="totalFee" disabled>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="paidAt">Tanggal Pembayaran</label>
                      <input type="date" class="form-control" id="paidAt" v-model="paidAt">
                    </div>
                  </div>
                </div>
              </div>

              <div class="card-footer">
                <button type="submit" class="btn btn-success">Simpan</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>