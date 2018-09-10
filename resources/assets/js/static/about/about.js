"use strict";
import './../../bootstrap';

import Bus from './../../Bus';
import router from './router';


import store from './store/store.js';

import RoompHeader from './../../components/RoompHeader/RoompHeader.vue';
import RoompFooter from './../../components/RoompFooter/RoompFooter.vue';

import {mapGetters} from 'vuex';

document.addEventListener('DOMContentLoaded', e => {
  const App = new Vue({
    data: {
      Bus: Bus
    },
    el: 'main',
    store,
    router,
    components: {
      RoompHeader, RoompFooter
    },
  });
});