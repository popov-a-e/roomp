"use strict";
import './../../bootstrap';

import Bus from './../../Bus';

import store from './store/store.js';
import router from './router';

import RoompHeader from './../components/RoompHeader/RoompHeader.vue';
import Profile from './components/Profile.vue';

const App = new Vue({
  data: {
    Bus: Bus
  },
  el: 'main',
  store,
  router,
  components: {
    RoompHeader, Profile
  }
});