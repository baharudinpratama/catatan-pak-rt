<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import api from '../../../api';

const router = useRouter();

const fetchDataExpenses = async () => {
  await api.get('/api/maintenance-payments')
    .then(response => {

      $('#paymentsTable').DataTable({
        'destroy': true,
        'autoWidth': false,
        'responsive': true,
        'processing': true,
        'searching': true,
        'lengthChange': true,
        'ordering': true,
        'data': response.data.data,
        'columns': [
          { 'data': 'resident_house.house.house_number', 'className': 'text-center house-number', },
          { 'data': 'resident_house.resident.name', 'className': 'resident', },
          { 'data': 'maintenance_type.name', 'className': 'type', },
          { 'data': 'amount', 'className': 'text-right fee', },
          { 'data': 'month', 'className': 'text-center month', },
          { 'data': 'year', 'className': 'text-center year', },
          {
            'data': 'is_paid',
            'className': 'text-center is-paid',
            'render': (data, type, row, meta) => {
              let badgeColor = row.is_paid == 1 ? 'bg-success' : 'bg-info';
              return `<span class="badge ${badgeColor}">${row.is_paid_text}</span>`;
            },
          },
          {
            'data': 'id',
            'className': 'text-center id',
            'render': (data, type, row, meta) => {
              let is_disabled = row.is_paid == 1 ? 'disabled' : '';
              return `
              <button data-id="${data}" class="btn btn-sm btn-success rounded-sm pay-btn" ${is_disabled}>
                <i class="fas fa-hand-holding-usd mr-2"></i>
                Bayar
              </button>
            `;
            },
          },
        ],
      });

      $('#paymentsTable tbody').on('click', '.pay-btn', function () {
        const id = $(this).data('id');
        router.push({ name: 'incomes.show', params: { id: id } });
      });

      let wrapper = $('#paymentsTable_wrapper .row');
      if (wrapper.length > 3) {
        for (let i = wrapper.length - 1; i <= wrapper.length; i++) {
          wrapper.eq(i).remove();
        }
        wrapper.eq(0).remove();
      }
    })
    .catch((error) => {
      toastr.error(error.response.data.message ?? 'Maaf terjadi kesalahan');
    });
}

onMounted(() => {
  fetchDataExpenses();
});
</script>

<template>
  <div class="col-md-12">
    <div class="card card-secondary">
      <div class="card-header">
        <h3 class="card-title">Riwayat Tagihan dan Pendapatan</h3>
        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
            <i class="fas fa-minus"></i>
          </button>
        </div>
      </div>

      <div class="card-body">
        <table id="paymentsTable" class="table table-bordered">
          <thead>
            <tr>
              <th class="text-center">Nomor Rumah</th>
              <th class="text-center">Penghuni</th>
              <th class="text-center">Jenis</th>
              <th class="text-center">Biaya</th>
              <th class="text-center">Bulan</th>
              <th class="text-center">Tahun</th>
              <th class="text-center">Status</th>
              <th class="text-center">Aksi</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</template>