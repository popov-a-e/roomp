"use strict";

export default {
  namespaced: true,
  state: {
    user: null,
    hotels: [],
    hotel: null
  },
  mutations: {
    initialize: (state, payload) => {
      state.hotels = payload.hotels;
      state.hotel = payload.hotel;
    },
  },
  actions: {
    setHotel({}, payload) {
      axios.get('/change_hotel/' + payload).then(r => {
        window.location.reload();
      });
    }
  }
}