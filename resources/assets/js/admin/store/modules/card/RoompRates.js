"use strict";

import mapper from '../../../helpers/mapper';

const store = {
  state () {
    return {
      hotelID: null,
    }
  },
  mutations: {
    setHotelID: (state, id) => state.hotelID = id,
  },
  actions: {
    load ({commit, state}) {
      axios.get(`/hotels/${state.hotelID}/rates`).then(({data: {discount, margin}}) => {
        commit('setDiscount', discount);
        commit('setMargin', margin);
      });
    },

    update({commit, state}) {
      axios.put(`/hotels/${state.hotelID}/rates`, only(state, ['discount', 'margin'])).then(() => {
        alert('OK');
      });
    },

    tiePrices({state, commit}) {
      commit('setTieState', 'Загрузка...');
      axios.get(`/channels/tie/${state.hotelID}/${state.rate_margin}`)
        .then(response => commit('setTieState', 'Успешно!'))
        .catch(response => commit('setTieState', 'Ошибка!'));
    }
  }
};

export default mapper({
  discount: null,
  margin: null,

  tie_state: null
}, store);