<script setup>
import { useRoute } from 'vue-router';

const route = useRoute();

const isActiveRoute = (routeName) => {
  return route.name == routeName;
};
</script>

<template>
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>
  </nav>

  <div class="sidebar-wrapper">
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <a href="index3.html" class="brand-link">
        <img src="/admin-lte/img/c-logo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
          style="opacity: .8">
        <span class="brand-text font-weight-light">
          CaPT
          <span class="text-xs">(Catatan Pak RT)</span>
        </span>
      </a>

      <div class="sidebar">

        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image">
            <img src="/admin-lte/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
          </div>
          <div class="info">
            <a href="#" class="d-block">Pak RT</a>
          </div>
        </div>

        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
              <RouterLink :to="{ name: 'home' }" class="nav-link" :class="{ active: isActiveRoute('home') }">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>Dashboard</p>
              </RouterLink>
            </li>

            <li class="nav-item">
              <RouterLink :to="{ name: 'houses' }" class="nav-link" :class="{ active: isActiveRoute('houses') }">
                <i class="nav-icon fas fa-home"></i>
                <p>Hunian</p>
              </RouterLink>
            </li>

            <li class="nav-item">
              <RouterLink :to="{ name: 'residents' }" class="nav-link" :class="{ active: isActiveRoute('residents') }">
                <i class="nav-icon fas fa-user"></i>
                <p>Penghuni</p>
              </RouterLink>
            </li>

            <li class="nav-item">
              <RouterLink :to="{ name: 'incomes' }" class="nav-link" :class="{ active: isActiveRoute('incomes') }">
                <i class="nav-icon fas fa-wallet"></i>
                <p>Pendapatan</p>
              </RouterLink>
            </li>

            <li class="nav-item">
              <RouterLink :to="{ name: 'expenses' }" class="nav-link" :class="{ active: isActiveRoute('expenses') }">
                <i class="nav-icon fas fa-money-bill-wave"></i>
                <p>Pengeluaran</p>
              </RouterLink>
            </li>
          </ul>
        </nav>
      </div>
    </aside>
  </div>

  <routerView></routerView>
</template>
