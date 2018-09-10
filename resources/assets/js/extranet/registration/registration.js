"use strict";

import '../../bootstrap.js';

import Bus from '../../Bus';

import store from './store/store.js';

import ExtranetRegistration from './components/ExtranetRegistration.vue';

const App = new Vue({
  data: {
    Bus: Bus
  },
  el: 'main',
  store,
  components: {
    ExtranetRegistration
  }
});