<script setup>
import { ref } from 'vue';
import FormCard from './components/FormCard.vue';
import HistoryCard from './components/HistoryCard.vue';

const refreshKey = ref(0);

const handleRefresh = () => {
  refreshKey.value++;
}
</script>

<template>
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Pengeluaran</h1>
        </div>
      </div>
    </div>
  </section>

  <section class="content">
    <div class="col-12">
      <div class="row">
        <FormCard @update="handleRefresh" />
        <HistoryCard :refresh="refreshKey"/>
      </div>
    </div>
  </section>
</template>