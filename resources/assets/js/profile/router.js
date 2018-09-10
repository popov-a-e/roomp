import UserInfo from './components/UserInfo.vue';
import Reservations from './components/Reservations.vue';

import VueRouter from 'vue-router';
Vue.use(VueRouter);

export default new VueRouter({
  routes: [
    { path: '/', component: UserInfo },
    { path: '/reservations', component: Reservations }
  ]
});