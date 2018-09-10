"use strict";
import Header from '../../../../components/RoompHeader/store/Header';
import overlayPlugin from '../../../../plugins/overlayPlugin';

export default new Vuex.Store({
  state: {
    cities: [],
    menuVisible: false
  },
  mutations: {
    initialize: (state, params) => {
      state.cities = params.cities;
    },
    toggleMenu: state => state.menuVisible = !state.menuVisible,
  },
  actions: {
  },
  getters: {},
  modules: {
    Header
  },

  plugins: [overlayPlugin]
});