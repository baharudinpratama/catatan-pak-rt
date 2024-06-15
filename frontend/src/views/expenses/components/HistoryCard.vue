<script setup>
import { ref, onMounted, defineProps, watch } from 'vue';
import api from '../../../api';
import moment from 'moment';

const props = defineProps(['refresh']);

const fetchDataExpenses = async () => {
  await api.get('/api/expenses')
    .then(response => {

      $('#expensesTable').DataTable({
        'destroy': true,
        'autoWidth': false,
        'responsive': true,
        'processing': true,
        'searching': true,
        'lengthChange': true,
        'ordering': true,
        'order': [[0, 'desc']],
        'data': response.data.data,
        'columns': [
          {
            'data': 'date',
            'width': '20%',
            'render': (data) => {
              return moment(data).format('DD MMM YYYY');
            },
            'type': 'date',
          },
          { 'data': 'description', 'width': '50%', },
          { 'data': 'amount', className: 'text-right', },
        ],
      });

      let wrapper = $('#expensesTable_wrapper .row');
      if (wrapper.length > 3) {
        for (let i = wrapper.length - 1; i <= wrapper.length; i++) {
          wrapper.eq(i).remove();
        }
        wrapper.eq(0).remove();
      }
    })
    .catch(error => {
      toastr.error(error.response.data.message ?? 'Maaf terjadi kesalahan');
    });
}

watch(() => props.refresh, (newVal, oldVal) => {
  if (newVal !== oldVal) {
    fetchDataExpenses();
  }
});

onMounted(() => {
  fetchDataExpenses();
});
</script>

<template>
  <div class="col-md-6">
    <div class="card card-secondary">
      <div class="card-header">
        <h3 class="card-title">Riwayat Pengeluaran</h3>
        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
            <i class="fas fa-minus"></i>
          </button>
        </div>
      </div>

      <div class="card-body">
        <table id="expensesTable" class="table table-bordered">
          <thead>
            <tr>
              <th class="text-center">Tanggal</th>
              <th class="text-center">Deskripsi</th>
              <th class="text-center">Nilai</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</template>