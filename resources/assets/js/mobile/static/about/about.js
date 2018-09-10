"use strict";
import './../../../bootstrap';

import router from './router';

import Bus from '../../../Bus';

import store from './store/store.js';

import RoompHeader from './../../components/RoompHeader/RoompHeader.vue';

import {mapGetters} from 'vuex';

document.addEventListener('DOMContentLoaded', e => {
  const App = new Vue({
    el: 'main',
    data: {
      Bus
    },
    store,
    router,
    components: {
      RoompHeader
    },
  });
});