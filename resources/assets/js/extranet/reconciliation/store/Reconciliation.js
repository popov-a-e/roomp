"use strict";

import {escapeRegExp} from '../../../helpers';

export default {
  namespaced: true,
  state() {

    let month = new Date().getMonth();
    let year = new Date().getFullYear();

    return {
      filters: {},
      sortBy: [],
      reservations: [],
      rooms: null,
      month, year,
    }
  },

  mutations: {
    setCodeFilter: (state, value) => Vue.set(state.filters, 'code', value),
    setStatusNameFilter: (state, value) => Vue.set(state.filters, 'status_name', value),
    setCityFilter: (state, value) => Vue.set(state.filters, 'city', value),
    setGuestNameFilter: (state, value) => Vue.set(state.filters, 'guest_name', value),
    setSort: (state, value) => state.sortBy = value,
    setRooms: (state, value) => state.rooms = value,

    setReservations: (state, value) => state.reservations = value,

    setMonth: (state, value) => state.month = value,
    setYear: (state, value) => state.year = value,
  },

  actions: {
    load({state, commit}) {
      const params = only(state, ['month', 'year']);

      axios.get('/reconcilitaion', {params}).then(response => {
        commit('setReservations', response.data);
      });
    },
  },

  getters: {
    totalSum: state => {
      let totalSum = 0;
      state.reservations.forEach(function (reservation) {
        totalSum += reservation.total
      });
      return totalSum.toFixed(2)
    },
    totalRateSum: state => state.reservations.sum('rate').toFixed(2),
    reservationsFiltered: state => {

      let array = state.reservations.filter(r => {
        let result = true;

        for (let key in state.filters) {
          if (!state.filters.hasOwnProperty(key) || !state.filters[key]) continue;

          if (state.filters[key] instanceof Array) {
            let preresult = false;

            state.filters[key].forEach(str => {
              const needle = new RegExp(escapeRegExp(str), 'i');

              preresult = preresult || !!r[key].match(needle);
            });

            if (state.filters[key].length === 0) preresult = true;

            result &= preresult;
          } else {
            const needle = new RegExp(escapeRegExp(state.filters[key]), 'i');

            result = result && r[key].match(needle);
          }
        }

        return result;
      });


      if (state.sortBy.length !== 2) return array;

      const desc = Number(state.sortBy[1] === 'desc') * 2 - 1;
      const key = state.sortBy[0];

      return array.sort((a, b) => {
        if (a[key] === b[key]) return 0;

        return a[key] > b[key] ? desc : -desc;
      });
    }
  }


};
