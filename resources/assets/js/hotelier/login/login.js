"use strict";

import '../../bootstrap.js';

import Bus from '../../Bus';

import store from './store/store.js';

import HotelierLogin from './components/HotelierLogin.vue';
import HotelierHeader from '../components/HotelierHeader/HotelierHeader.vue';
import HotelierFooter from '../components/HotelierFooter/HotelierFooter.vue';

const App = new Vue({
  data: {
    Bus: Bus
  },
  el: 'main',
  store,
  components: {
    HotelierLogin, HotelierHeader, HotelierFooter
  }
});