"use strict";

import { escapeRegExp } from '../../../../helpers';

export default {
  namespaced: true,
  state () {
    return {
      reservations: [],
      filters: {},
      sortBy: []
    }
  },
  mutations: {
    setReservations: (state, reservations) => state.reservations = reservations,
    setStatusFilter: (state, value) => Vue.set(state.filters, 'status_name', value),
    setHotelFilter: (state, value) => Vue.set(state.filters, 'hotel', value),
    setCodeFilter: (state, value) => Vue.set(state.filters, 'code', value),
    setCityFilter: (state, value) => Vue.set(state.filters, 'city', value),
    setGuestFilter: (state, value) => Vue.set(state.filters, 'guest_name', value),

    setSort: (state, value) => state.sortBy = value,

    rowClick: (state, id) => {
      window.location.href = `/reservations/${id}`;
    },

    delete: (state, payload) => state,

    setShow: (state, payload) => {
      const index = state.reservations.findIndex(r => r === payload.reservation);

      let r = payload.reservation;
      r.show = Boolean(Number(payload.value));

      state.reservations.splice(index, 1, r);
    },
    reset: state => {
      state.reservations = state.reservations.map(r => {
        delete r.show;

        return r;
      });
    }
  },
  actions: {
    apply ({state}) {
      axios.post('/reservations', {reservations: state.reservations.pluck('id', 'show')}).then(() => window.location.reload());
    },
  },
  getters: {
    reservationsFiltered: state => {
      let array = state.reservations.filter(r => {
        let result = true;

        for (let key in state.filters) {
          if (!state.filters.hasOwnProperty(key) || !state.filters[key]) continue;

          const needle = new RegExp(escapeRegExp(state.filters[key]),'i');

          result = result && r[key].match(needle);
        }

        return result;
      });


      if (state.sortBy.length !== 2) return array;

      const desc = Number(state.sortBy[1] === 'desc') * 2 - 1;
      const key = state.sortBy[0];


      return array.sort((a,b) => {
        if (a[key] === b[key]) return 0;

        return a[key] > b[key] ? desc : -desc;
      });
    },
    shows: state => state.reservations.some(r => r.hasOwnProperty('show'))
  }
}