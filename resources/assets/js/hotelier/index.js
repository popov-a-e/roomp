"use strict";

import '../bootstrap.js';

import Bus from '../Bus';

import store from './store/store.js';
import router from './router';

import HotelierHeader from './components/HotelierHeader/HotelierHeader.vue';
import HotelierFooter from './components/HotelierFooter/HotelierFooter.vue';
/*import HotelierAvailability from './components/HotelierAvailability.vue';
import HotelierReservations from './reservations/HotelierReservations.vue';*/

window.addEventListener('keydown', e => {
  Bus.$emit('keydown', e);
});

const App = new Vue({
  data: {
    Bus: Bus
  },
  el: 'main',
  store,
  router,
  components: {
    HotelierHeader, HotelierFooter
  }
});