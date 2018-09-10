"use strict";

export default {
  namespaced: true,
  state: () => ({
    hotels: [],
    hotel: null,

    menuVisible: false
  }),
  mutations: {
    initialize: (state, payload) => {
      state.hotels = payload.hotels;
      state.hotel = payload.hotel;
    },

    showMenu: state => state.menuVisible = true,
    hideMenu: state => state.menuVisible = false,

    toggleMenu: state => state.menuVisible = !state.menuVisible,

    setHotels: (state, hotels) => state.hotels = hotels,
  },
  actions: {/*
    setHotel({}, payload) {
      axios.get('/change_hotel/' + payload).then(r => {
        window.location.reload();
      });
    },*/

    changeLocale({}, payload) {
      axios.get('/select_locale/' + payload).then(r => {
        window.location.reload();
      });
    }
  }
}