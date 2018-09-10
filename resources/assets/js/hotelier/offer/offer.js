"use strict";

import Vue from 'vue';
import axios from 'axios';

const App = new Vue({
  data: {
    acceptance: false,
    accepted: false,
    loading: false
  },
  el: 'main',
  methods: {
    changeHotel(e) {
      const value = e.target.value;
      git remote remove bitbucket
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