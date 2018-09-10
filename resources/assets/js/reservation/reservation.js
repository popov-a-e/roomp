"use strict";
import './../bootstrap';

import Bus from './../Bus';

import store from './store/store.js';

//import RoompHeader from './../components/RoompHeader/RoompHeader.vue';
//import RoompForm from './components/RoompForm.vue';

import { mapActions, mapGetters } from 'vuex';
import PaymentStatus from './components/PaymentStatus.vue';


const App = new Vue({
  data: {
    Bus: Bus
  },
  el: 'main',
  store,
  components: {
    PaymentStatus//RoompHeader
  },
  computed: {
    ...mapGetters(['paymentStatus'])
  },
  methods: mapActions(['cancel', 'pay']),
  created () {
    const reservationData =  window.location.href.match(/\/r\/([\w\d]+)\?token=([\w\d]+)/);
    this.$store.commit('initialize', {
      code: reservationData[1],
      token: reservationData[2]
    })
  }
});