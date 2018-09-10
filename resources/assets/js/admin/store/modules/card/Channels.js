"use strict";

import mapper from '../../../helpers/mapper';

const store = {
  namespaced: true,
  state() { return {} },
  mutations: {},
  actions: {
    load ({state, commit}) {

    },

    syncAvailability({state, commit}) {
      commit('setAvailabilityState', 'Загрузка...');
        axios.get(`/hotels/${state.hotelID}/channels/sync/availability`)
        .then(response => commit('setAvailabilityState', 'Успешно!'))
        .catch(response => commit('setAvailabilityState', 'Ошибка!'));
    },

    syncPrices ({state, commit}) {
      commit('setPricesState', 'Загрузка...');
      axios.get(`/hotels/${state.hotelID}/channels/sync/prices`)
        .then(response => commit('setPricesState', 'Успешно!'))
        .catch(response => commit('setPricesState', 'Ошибка!'));
    },

    closeAvailability ({state, commit}) {
      commit('setCloseState', 'Загрузка...');
      axios.get(`/hotels/${state.hotelID}/channels/close`)
        .then(response => commit('setCloseState', 'Успешно!'))
        .catch(response => commit('setCloseState', 'Ошибка!'));
    },

    tiePrices({state, commit}) {
      commit('setTieState', 'Загрузка...');
      axios.get(`/hotels/${state.hotelID}/channels/tie`)
        .then(response => commit('setTieState', 'Успешно!'))
        .catch(response => commit('setTieState', 'Ошибка!'));
    }
  }
};

export default mapper({
  hotelID: null,
  channels: {default: []},
  wubook_id: 'setWubookID',
  wubook_state: null,
  availability_state: null,
  prices_state: null,
  close_state: null,
  tie_state: null,
  margin: null
},store);
