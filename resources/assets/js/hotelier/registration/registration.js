"use strict";

import '../../bootstrap.js';

import Bus from '../../Bus';

import store from './store/store.js';

import HotelierRegistration from './components/HotelierRegistration.vue';
import HotelierHeader from '../components/HotelierHeader/HotelierHeader.vue'
import HotelierFooter from '../components/HotelierFooter/HotelierFooter.vue'

const App = new Vue({
  data: {
    Bus: Bus
  },
  el: 'main',
  store,
  components: {
    HotelierRegistration, HotelierHeader, HotelierFooter
  }
});