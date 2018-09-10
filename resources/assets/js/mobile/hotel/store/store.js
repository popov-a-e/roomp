"use strict";

import Header from './../../../components/RoompHeader/store/Header';

import Cart from '../../../hotel/store/modules/Cart';
import BackendData from '../../../hotel/store/modules/BackendData';
import Gallery from '../../../hotel/store/modules/Gallery';
import Datepicker from '../../components/Datepicker/store';

import CurrentRoom from './modules/CurrentRoom';
import Appearance from './modules/Appearance';

import overlayPlugin from '../../../plugins/overlayPlugin';

import {state, mutations, actions, getters, book} from '../../../hotel/store/store';


state.mounted = false;
state.code_promise = false;

mutations.setCodePromise = (state, promise) => state.code_promise = promise;
mutations.mount = state => state.mounted = true;
mutations.enableDatepicker = (state, {from, till, direction, resolve}) => {
  state.Datepicker.from = new Date(from.getFullYear(), from.getMonth(), from.getDate(), 0, 0, 0, 0);
  state.Datepicker.till = new Date(till.getFullYear(), till.getMonth(), till.getDate(), 0, 0, 0, 0);
  state.Datepicker.resolve = resolve;
  state.Datepicker.direction = direction;
  state.Appearance.datepickerVisible = true;
  window.overlayCount++;
};


actions.pay = ({commit, dispatch, state}) => {
  if (!state.Header.user) {
    let promise = new Promise((resolve, reject) => {
      commit('setPromise', {resolve, reject});
      commit('Appearance/togglePaymentOverlay');

      new Promise((resolve, reject) => {
        commit('setCodePromise', {resolve, reject});
      }).then(() => commit('Header/Appearance/toggleMenu'));

      dispatch('Header/register');
    });

    promise.then(() => {
      commit('setPromise', false);
      commit('setCodePromise', false);
      book(state, true);
    }).catch(() => {
      commit('setPromise', false);
      commit('setCodePromise', false);
    });
  } else {
    book(state, true);
  }
};

actions.book = ({commit, state, dispatch}) => {
  if (!state.Header.user) {
    let promise = new Promise((resolve, reject) => {
      commit('setPromise', {resolve, reject});

      new Promise((resolve, reject) => {
        commit('setCodePromise', {resolve, reject});
      }).then(() => commit('Header/Appearance/toggleMenu'));

      dispatch('Header/register');
    });

    promise.then(() => {
      commit('setPromise', false);
      commit('setCodePromise', false);
      book(state, false);
    }).catch(() => {
      commit('setPromise', false);
      commit('setCodePromise', false);
    });
  } else {
    book(state, false);
  }
};

export default new Vuex.Store({
  state,

  mutations,

  actions,

  getters,

  modules: {
    Appearance, Cart, BackendData, Header, CurrentRoom, Gallery, Datepicker
  },

  plugins: [overlayPlugin]
});