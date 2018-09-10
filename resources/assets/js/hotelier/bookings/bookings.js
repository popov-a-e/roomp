"use strict";

import '../../bootstrap.js';

import Bus from '../../Bus';

import store from './store/store.js';

import HotelierHeader from '../components/HotelierHeader/HotelierHeader.vue';
import UncheckedReservations from './components/UncheckedReservations.vue';

window.addEventListener('keydown', e => {
  Bus.$emit('keydown', e);
});

const App = new Vue({
  data: {
    Bus: Bus
  },
  el: 'main',
  store,
  components: {
    UncheckedReservations, HotelierHeader
  }
});