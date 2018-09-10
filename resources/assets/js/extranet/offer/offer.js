"use strict";

import '../../bootstrap.js';

import Vue from 'vue';
import axios from 'axios';

import HotelSelector from './HotelSelector.vue';
import NewHotelForm from './NewHotelForm.vue';

const App = new Vue({
  components: {HotelSelector, NewHotelForm},
  data: {
    acceptance: false,
    accepted: false,
    loading: false
  },
  el: 'main',
  methods: {
    changeHotel(e) {
      const value = e.target.value;

      axios.get('/change_hotel/' + value).then(response => window.location.reload());
    },
    accept() {
      this.loading = true;
      axios.get('/accept_offer/').then(response => {
        this.accepted = true;
        this.loading = false;
      });
    }
  }
});