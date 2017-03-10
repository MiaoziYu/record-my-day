import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router);

import DashboardComponent from '../components/dashboard.vue'
import RecordsComponent from '../components/records.vue'

const router = new Router({
    routes: [
        {
            path: '/',
            name: 'dashboard',
            component: DashboardComponent
        },
        {
            path: '/records',
            name: 'records',
            component: RecordsComponent
        },
    ]
});

export default router