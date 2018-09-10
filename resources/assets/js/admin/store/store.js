"use strict";

import Header from '../components/AdminHeader/store/Header';
import Reservations from './modules/Reservations';
import Hoteliers from './modules/Hoteliers';
import Meta from './modules/Meta';
import ChannelInfo from './modules/ChannelInfo';
import APIChannels from './modules/APIChannels';

import Router from './router';
import createPersistedState from './plugins/storage';


export default new Vuex.Store({
  state: {
  },

  mutations: {
  },

  modules: {
    Header, Router, Meta, ChannelInfo
  },

  //plugins: [createPersistedState()]
});