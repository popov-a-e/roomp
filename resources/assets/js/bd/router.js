import Index from '../admin/index/AdminIndex.vue';
import Onboarding from './hotels/Onboarding.vue';
import HotelList from './hotels/HotelList.vue';
import Stub from '../admin/router/Stub.vue';
import Hotel from './hotels/Hotel/Hotel.vue';

import OfferStatus from './hotels/Hotel/OfferStatus.vue';
import HotelOffer from './hotels/Hotel/HotelOffer.vue';
import HotelStatusSelector from './hotels/Hotel/HotelStatusSelector.vue';


import VueRouter from 'vue-router';
Vue.use(VueRouter);

const router = new VueRouter({
  routes: [
    {
      path: '/',
      meta: {name: 'Главная'},
      component: Index,
      children: [
        {path: '', redirect: '/onboarding'},
        {path: 'onboarding', meta: {name: 'Onboarding'}, component: Onboarding,
          children: [
            {path: '', redirect: 'initial'},
            {path: 'initial', component: HotelList, props: {stage: 'initial'}},
            {path: 'signing', component: HotelList, props: {stage: 'signing'}},
            {path: 'active', component: HotelList, props: {stage: 'active'}},
            {path: 'thinking', component: HotelList, props: {stage: 'thinking'}},
            {path: 'ready', component: HotelList, props: {stage: 'ready'}},
            {path: 'deleted', component: HotelList, props: {stage: 'deleted'}},

            {path: ':hotel_id', component: Hotel, props: true,
              children: [
                {path: '', props: true, component: OfferStatus},
                {path: 'edit', props: true, component: HotelOffer},
                {path: 'select_status', props: true, component: HotelStatusSelector}
 //               {path: 'info', meta: {name: 'Инфо',}, props: true, component: HotelInfo},

              ]
            },
          ]
        },
      ]
    },
    { path: '*', redirect: '/' }
  ]
});


export default router;