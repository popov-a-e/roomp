"use strict";
import './../../bootstrap';

import Bus from './../../Bus';


import store from './store/store.js';

import RoompHeader from './../../components/RoompHeader/RoompHeader.vue';
import RoompFooter from './../../components/RoompFooter/RoompFooter.vue';

import {mapGetters} from 'vuex';

const App = new Vue({
  data: {
    Bus: Bus
  },
  el: 'main',
  store,
  components: {
    RoompHeader, RoompFooter
  },
});