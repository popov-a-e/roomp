"use strict";

import '../bootstrap.js';

import Bus from '../Bus';
import router from './router';

require('./listeners.js');

import store from './store/store.js';

import BDHeader from './components/Header/BDHeader.vue';
import NewHotel from './hotels/Hotel/NewHotel.vue';
import OrganizationSelector from './hotels/OrganizationSelector.vue';
import HotelierSelector from './hotels/HotelierSelector.vue';
import Room from './hotels/Rooms/Room.vue';

Vue.use(Vuex);

import {mapState, mapMutations} from 'vuex';

const App = new Vue({
  data: {Bus},
  el: 'main',
  store,
  router,
  components: {
    BDHeader, NewHotel, Room, OrganizationSelector, HotelierSelector
  },
  computed: {
    ...mapState('Onboarding', ['addingHotel']),
    ...mapState('Hotel/Rooms', {selectedRoom: 'selected'}),
    ...mapState('Hotel', ['organizationSelectorVisible', 'hotelierSelectorVisible'])
  },
  methods: {
    ...mapMutations('Onboarding', {hideHotel: 'hideModal'}),
    ...mapMutations('Hotel/Rooms', ['hideRoom']),
    ...mapMutations('Hotel', ['hideOrganizationSelector', 'hideHotelierSelector'])
  },
  created () {
    this.$store.dispatch('Meta/loadAll');
  }
});