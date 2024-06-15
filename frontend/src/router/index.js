import { createRouter, createWebHistory } from 'vue-router';

// import HomeView from './HomeView.vue'
// import AboutView from './AboutView.vue'

const routes = [
    {
        path: '/',
        name: 'home',
        component: () => import('../views/Home.vue'),
    },
    {
        path: '/residents',
        name: 'residents',
        component: () => import('../views/residents/Index.vue'),
    },
    {
        path: '/residents/create',
        name: 'residents.create',
        component: () => import('../views/residents/Create.vue'),
    },
    {
        path: '/residents/edit/:id',
        name: 'residents.edit',
        component: () => import('../views/residents/Edit.vue'),
    },
    {
        path: '/houses',
        name: 'houses',
        component: () => import('../views/houses/Index.vue'),
    },
    {
        path: '/houses/create',
        name: 'houses.create',
        component: () => import('../views/houses/Create.vue'),
    },
    {
        path: '/houses/edit/:id',
        name: 'houses.edit',
        component: () => import('../views/houses/Edit.vue'),
    },
    {
        path: '/expenses',
        name: 'expenses',
        component: () => import('../views/expenses/Index.vue'),
    },
    {
        path: '/incomes',
        name: 'incomes',
        component: () => import('../views/incomes/Index.vue'),
    },
    {
        path: '/incomes/show/:id',
        name: 'incomes.show',
        component: () => import('../views/incomes/Show.vue'),
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
