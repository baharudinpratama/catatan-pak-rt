<script setup>
import { computed } from 'vue';
import { defineProps } from 'vue';

const props = defineProps({
  house: {
    type: Object,
    required: true
  }
});

const getClass = (house, prop) => {
  if (house.active == false) {
    return `${prop}-danger`;
  } else if (house.has_resident == true) {
    return `${prop}-primary`;
  } else if (house.has_resident == false) {
    return `${prop}-success`;
  }
  return '';
};

const cardClass = computed(() => getClass(props.house, 'card'));
const textClass = computed(() => getClass(props.house, 'text'));
const btnClass = computed(() => getClass(props.house, 'btn'));
</script>

<template>
  <div class="card card-outline" :class="cardClass">
    <img :src="house.house_image" class="card-img-top overflow-hidden" :alt="house.house_image" height="200px">
    <div class="card-body box-profile">
      <h5 class="profile-username text-center">Nomor : {{ house.house_number }}</h5>
      <p class="text-muted text-center">{{ house.description }}</p>
      <ul class="list-group list-group-unbordered mb-3">
        <li class="list-group-item">
          <b>Status</b>
          <a class="float-right" :class="textClass">
            {{ house.has_resident_text }}
          </a>
        </li>
      </ul>
      <RouterLink :to="{ name: 'houses.edit', params: { id: house.id } }" class="btn btn-block" :class="btnClass">
        <b>Detail</b>
      </RouterLink>
    </div>
  </div>
</template>