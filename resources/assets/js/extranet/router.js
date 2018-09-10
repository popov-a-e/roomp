import VueRouter from 'vue-router';
import ExtranetIndex from './index/ExtranetIndex.vue';
import Dashboard from './dashboard/Dashboard.vue';
import Reservations from './reservations/Reservations.vue'
import Finance from './finance/Finance'
import Help from './index/Help.vue'
import Reconciliation from './reconciliation/Reconciliation'
import BookingConformation from './reservations/confirmation/ReservationConfirmation'
import Organization from './organization/Organization.vue'

Vue.use(VueRouter);


const router = new VueRouter({
  routes: [
    {path: '/', meta: {name: 'Главная'}, component: ExtranetIndex,
      children: [
        {path: '', redirect: '/dashboard'},
        {path: 'dashboard', component: Dashboard, props: true},
        {path: 'reservations', component: Reservations, props: true},
        {path: 'reconciliation', component: Reconciliation, props: true},
        {path: 'finance', component: Finance, props: true},
        {path: 'help', component: Help, props: true},
        {path: 'booking_confirmation', component: BookingConformation, props: true},
        {path: 'organization', component: Organization, props: true}
      ]
    },
    { path: '*', redirect: '/' }
  ]
});

export default router;