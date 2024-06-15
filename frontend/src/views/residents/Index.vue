<script setup>
import { onMounted } from 'vue';
import { useRouter } from 'vue-router';
import api from '../../api';

const router = useRouter();

const fetchDataResidents = async () => {
  await api.get('/api/residents').then(response => {
    $('#residentsTable').DataTable({
      'destroy': true,
      'autoWidth': false,
      'responsive': true,
      'processing': true,
      'searching': true,
      'lengthChange': true,
      'ordering': true,
      'data': response.data.data,
      'columns': [
        { 'data': 'name', },
        { 'data': 'id_card_number', },
        {
          'data': 'id_card_image',
          'className': 'text-center',
          'render': (data, type, row, meta) => {
            if (data == null) {
              return '';
            } else {
              return `<img src="${data}" alt"${data}" height="56px" />`
            }
          }
        },
        { 'data': 'phone_number', },
        {
          'data': 'id',
          'render': (data, type, row, meta) => {
            if (row.is_resident == false) {
              return 'Bukan penghuni'
            }
            return row.resident_house.residential_status.name;
          }
        },
        { 'data': 'marital_status_text', },
        {
          'data': 'id',
          'className': 'text-center',
          'render': (data, type, row, meta) => {
            return `
            <button data-id="${data}" class="btn btn-sm btn-secondary rounded-sm edit-btn">Edit</button>
          `;
          },
        },
      ],
    });

    $('#residentsTable tbody').on('click', '.edit-btn', function () {
      const id = $(this).data('id');
      router.push({ name: 'residents.edit', params: { id: id } });
    });

    let wrapper = $('#residentsTable_wrapper .row');
    if (wrapper.length > 3) {
      for (let i = wrapper.length - 1; i <= wrapper.length; i++) {
        wrapper.eq(i).remove();
      }
      wrapper.eq(0).remove();
    }
  }).catch((error) => {
    toastr.error(error.response.data.message ?? 'Maaf terjadi kesalahan');
  });
}

onMounted(() => {
  fetchDataResidents();
});
</script>

<template>
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Data Penghuni</h1>
        </div>

        <div class="col-sm-6">
          <RouterLink :to="{ name: 'residents.create' }" class="btn btn-primary float-right">
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
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <div class="card-title">
                Tabel Data Penghuni
              </div>
            </div>

            <div class="card-body">
              <table id="residentsTable" class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th class="text-center">Nama</th>
                    <th class="text-center">Nomor Identitas</th>
                    <th class="text-center">Kartu Identitas</th>
                    <th class="text-center">Nomor Telepon</th>
                    <th class="text-center">Status Penghuni</th>
                    <th class="text-center">Status Pernikahan</th>
                    <th class="text-center">Aksi</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>