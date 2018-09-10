"use strict";

import Meta from '../../admin/store/modules/Meta';
import Onboarding from './modules/Onboarding';
import Room from './modules/Room';
import Hotel from './modules/Hotel';

export default new Vuex.Store({
  state: {
  },

  mutations: {

  },

  modules: {
    Meta, Onboarding, Hotel, Room
  },
});