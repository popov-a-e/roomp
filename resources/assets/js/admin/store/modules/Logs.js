"use strict";

import mapper from '../../helpers/mapper';

const store = {
  namespaced: true,
  actions: {
    load({state, commit}) {
      return new Promise(resolve => axios.get(`/hotels/${state.hotelID}/logs`).then(response => {
          commit('setLogs', response.data);
          resolve();
        })
      )
    },
    log({state, dispatch, commit}) {
      axios.put(`/hotels/${state.hotelID}/logs`, {message: state.message}).then(response => {
        dispatch('load').then(() => commit('setMessage', ''));
      });
    }
  },
};

export default mapper({logs: null, hotelID: null, message: null}, store);