"use strict";

import Header from './../../../../components/RoompHeader/store/Header';
import overlayPlugin from '../../../../plugins/overlayPlugin';

export default new Vuex.Store({
  state: {},
  modules: {
    Header
  },

  plugins: [overlayPlugin]
});