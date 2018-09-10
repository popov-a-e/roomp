"use strict";

import Header from './../../../components/RoompHeader/store/Header';
import Footer from './../../../components/RoompFooter/store/Footer';
import overlayPlugin from '../../../plugins/overlayPlugin';

export default new Vuex.Store({
  state: {},
  modules: {
    Header, Footer
  },

  plugins: [overlayPlugin]
});