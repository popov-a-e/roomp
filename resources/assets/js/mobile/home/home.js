"use strict";
import './../../bootstrap';

import Bus from './../../Bus';

import store from './store/store.js';

import {mapGetters} from 'vuex';

import RoompHeader from '../components/RoompHeader/RoompHeader.vue';
import RoompForm from './components/RoompForm.vue';

document.addEventListener('click', e => Bus.$emit('click', e));
document.addEventListener('mousemove', e => Bus.$emit('click', e.pageX, e.pageY, e));
document.addEventListener('mouseup', e => Bus.$emit('mouseup', e));
document.addEventListener('wheel', e => e.preventDefault());

import {mapState, mapMutations} from 'vuex';

const App = new Vue({
  components: {
    RoompHeader, RoompForm
  },
  data: {
    Bus
  },
  el: 'main',
  store,
  computed: {
    ...mapState('Header', ['contentHeight'])
  }
});
