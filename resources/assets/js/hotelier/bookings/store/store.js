"use strict";

import Header from '../../components/HotelierHeader/store/Header';
import Reservations from './modules/Reservations';

export default new Vuex.Store({
  state: {
  },

  mutations: {
  },

  modules: {
    Header, Reservations
  },

  //plugins: [createPersistedState()]
});