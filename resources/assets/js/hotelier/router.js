import VueRouter from 'vue-router';
import HotelierIndex from './index/HotelierIndex.vue';
import Calendar from './index/Calendar.vue'

Vue.use(VueRouter);


const router = new VueRouter({
  routes: [
    {path: '/', meta: {name: 'Главная'}, component: HotelierIndex,
      children: [
        {path: 'calendar', component: Calendar, props: true},
      ]
    },
    { path: '*', redirect: '/' }
  ]
});

export default router;