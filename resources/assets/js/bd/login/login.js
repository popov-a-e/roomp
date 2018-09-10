"use strict";

import '../../bootstrap.js';

import Bus from '../../Bus';

import store from './store/store.js';

import AdminLogin from './components/AdminLogin.vue';

const App = new Vue({
  data: {
    Bus: Bus
  },
  el: 'main',
  store,
  components: {
    AdminLogin
  },
});