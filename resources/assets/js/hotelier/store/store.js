"use strict";

import Header from '../components/HotelierHeader/store/Header';
import Availability from './modules/Availability';
import Reservations from './modules/Reservations';
import Rates from './modules/Rates';

export default new Vuex.Store({
  state: {
  },

  mutations: {
  },

  modules: {
    Header, Availability, Reservations, Rates
  },

  //plugins: [createPersistedState()]
});