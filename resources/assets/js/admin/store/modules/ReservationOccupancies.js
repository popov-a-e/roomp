"use strict";

import mapper from '../../helpers/mapper';

const store = {
  mutations: {
    toggleEdit: (state, occupancy) => {
      if (state.edited) {
        state.edited = false;
        state.occupancy_edited = {};
      } else {
        state.edited = occupancy.id;
        state.occupancy_edited = JSON.parse(JSON.stringify(occupancy));
      }
    },
    setRate: (state, payload) => {
      let occupancy = JSON.parse(JSON.stringify(state.occupancy_edited));
      let rates = occupancy.rates;

      rates.splice(payload.index, 1, Number(payload.value));

      occupancy.rates = rates;

      state.occupancy_edited = occupancy;
    },
    setPrice: (state, payload) => {
      let occupancy = JSON.parse(JSON.stringify(state.occupancy_edited));
      let prices = occupancy.prices;

      prices.splice(payload.index, 1, Number(payload.value));

      occupancy.prices = prices;

      state.occupancy_edited = occupancy;
    },
    setOccupancyAdults: (state, value) => Vue.set(state.occupancy_edited, 'adults', Number(value)),
    setOccupancyInfants: (state, value) => Vue.set(state.occupancy_edited, 'infants', Number(value)),
    setOccupancyChildren: (state, value) => Vue.set(state.occupancy_edited, 'children', Number(value)),
    setOccupancyPrice: (state, value) => Vue.set(state.occupancy_edited, 'price', Number(value)),

    editReservation: state => {
      state.from = new Date(state.reservation.from);
      state.till = new Date(state.reservation.tillFix);
      state.guest_name = state.reservation.guest_name;
      state.reservation_edited = true;
    },
    cancelEditingReservation: state => state.reservation_edited = false,
    setGuestName: (state, value) => state.guest_name = value,
    setDates: (state, payload) => {
      state.from = payload.from;
      state.till = payload.till;
    }
  },
  actions: {
    load({state, commit}) {
      axios.get(`/occupancies/${state.ID}`).then(response => {
        commit('setReservation', response.data);
        commit('setEdited', false);
        commit('setReservationEdited', false);
      });
    },
    save({state, dispatch}) {
      axios.post(`/occupancy`, {...state.occupancy_edited}).then(response => {
        dispatch('load');
      });
    },
    saveReservation({state, dispatch}) {
      if (!state.from || !state.till) return alert('Неверные даты заезда и выезда');

      axios.post(`/reservations`, {
        id: state.ID,
        from: state.from.toISODateString(),
        till: state.till.toISODateString(),
        guest_name: state.guest_name
      }).then(response => {
        dispatch('load');
      });
    },
  },
};

export default mapper({
  ID: null,
  reservation: null,
  edited: {default: false},
  reservation_edited: {default: false},
  occupancy_edited: {default: {}},
  from: null,
  till: null,
  guest_name: null
}, store);