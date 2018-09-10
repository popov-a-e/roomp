"use strict";
import './../../bootstrap';

import Bus from './../../Bus';

import store from './store/store.js';

import RoompHeader from './../../components/RoompHeader/RoompHeader.vue';
import RoompFooter from './../../components/RoompFooter/RoompFooter.vue';
import RoompForm from './components/RoompForm.vue';
import OverlayPromo from './../../components/OverlayPromo.vue';

import {mapGetters} from 'vuex';

document.addEventListener('wheel', function(e) {
  e.preventDefault();
});

const App = new Vue({
  data: {
    Bus: Bus
  },
  el: 'main',
  store,
  components: {
    RoompHeader, RoompForm, RoompFooter, OverlayPromo
  },
  methods: {
    wheel (e) {
      if (e.deltaY < 0 && this.$store.state.footerVisible) {
        this.$store.commit('footerToggle')
      } else if (e.deltaY > 0 && !this.$store.state.footerVisible) {
        this.$store.commit('footerToggle');
      }
    }
  },
  computed: mapGetters(['footerTop']),
  created () {
    document.addEventListener('keydown', e => Bus.$emit('keydown', e), false);
  }
});