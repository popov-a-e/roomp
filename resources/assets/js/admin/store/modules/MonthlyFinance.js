"use strict";

import mapper from '../../helpers/mapper';
import tableFactory from '../tableFactory';

const store = {
  state: () => ({

  }),
  mutations: {
    generated: (state, hotelID) => {
      Vue.set(state.hotels[hotelID], 'document_generated', true);
    }
  },
  actions: {
    load({state, commit}) {
      axios.get('/reconcilliation/report', {params: only(state, ['month', 'year'])}).then(response => commit('setHotels', response.data));
    },
    generate({state, commit}, hotel_id) {
      return new Promise(resolve => axios.get('/reconcilliation/generate', {params: {hotel_id,...only(state, ['month', 'year'])}}).then(response => {
        resolve();
        commit('generated', hotel_id);
      }));
    },

    sendEmail({state, commit}, hotel_id) {
      return new Promise(resolve => axios.get('/reconcilliation/send_email', {params: {hotel_id,...only(state, ['month', 'year'])}}).then(response => {
        resolve();
      }));
    }
  }

};

const date = new Date();

export default mapper({
  month: {default: date.getMonth() + 1},
  year: {default: date.getFullYear()},
  hotels: null
}, store);