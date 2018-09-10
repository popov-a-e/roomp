"use strict";

export default {
  namespaced: true,
  state: () => ({
    hotels: [],

    addingHotel: false
  }),
  mutations: {
    setHotels: (state, hotels) => state.hotels = hotels,

    showModal: state => {
      document.body.className = 'fixed';
      state.addingHotel = true;
    },

    hideModal: state => {
      document.body.className = '';
      state.addingHotel = false;
    },

    addHotel: (state, hotel) => state.hotels.push(hotel),

    moveHotel: (state, {hotel, stage}) =>  {
      const index = state.hotels.findIndex(h => h.id === hotel.id);
      Vue.set(state.hotels[index].lead, 'stage', stage);
    }
  },
  actions: {
    load ({state, commit}) {
      axios.get('/hotels').then(response => commit('setHotels', response.data));
    }
  },
  getters: {
    initial: state => state.hotels.filter(hotel => !hotel.lead || hotel.lead.stage === 'initial'),
    signing: state => state.hotels.filter(hotel => hotel.lead && hotel.lead.stage === 'signing'),
    active: state => state.hotels.filter(hotel => hotel.lead && hotel.lead.stage === 'active'),
    deleted: state => state.hotels.filter(hotel => hotel.lead && hotel.lead.stage === 'deleted'),
    thinking: state => state.hotels.filter(hotel => hotel.lead && hotel.lead.stage === 'thinking'),
    ready: state => state.hotels.filter(hotel => hotel.lead && hotel.lead.stage === 'ready'),

    initialNumber: (state, getters) => getters.initial.length,
    signingNumber: (state, getters) => getters.signing.length,
    activeNumber: (state, getters) => getters.active.length,

    numbers: (state, getters) => ({
      initial: getters.initial.length,
      signing: state.hotels.filter(hotel => hotel.lead && hotel.lead.stage === 'signing').length,
      active: state.hotels.filter(hotel => hotel.lead && hotel.lead.stage === 'active').length,
      deleted: state.hotels.filter(hotel => hotel.lead && hotel.lead.stage === 'deleted').length,
      thinking: state.hotels.filter(hotel => hotel.lead && hotel.lead.stage === 'thinking').length,
      ready: state.hotels.filter(hotel => hotel.lead && hotel.lead.stage === 'ready').length,
    })
  }
}