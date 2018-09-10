"use strict";

import { escapeRegExp } from '../../../helpers';

export default {
  namespaced: true,
  state() {
    let from = new Date();

    from.setHours(0,0,0,0);
    from.setDate(from.getDate() - 3);

    let till = new Date(+ from);

    till.setDate(till.getDate() + 30);

    return {
      filters: {},
      sortBy: [],
      type: 'created_at',
      from, till,
      reservations: [],
      rooms: null
    }
  },

  mutations: {
    setType: (state, value) => state.type = value,
    setFrom: (state, value) => state.from = value,
    setTill: (state, value) => state.till = value,

    setCodeFilter: (state, value) => Vue.set(state.filters, 'code', value),
    setStatusNameFilter: (state, value) => Vue.set(state.filters, 'status_name', value),
    //setCodeFilter: (state, value) => Vue.set(state.filters, 'code', value),
    setCityFilter: (state,value) => Vue.set(state.filters, 'city', value),
    setGuestNameFilter: (state, value) => Vue.set(state.filters, 'guest_name', value),

    setSort: (state, value) => state.sortBy = value,

    setReservations: (state, value) => state.reservations = value,

    setRooms: (state, value) => state.rooms = value,

  },

  actions: {
    load({state, commit}) {
      if (!state.from || !state.till) return ;

      const from = state.from.toISODateString(),
            till = state.till.toISODateString(),
            type = state.type;

      axios.get('/reservations', {params: {from, till, type}}).then(response => {
        commit('setReservations', response.data);
      });
    },
  },

  getters: {
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
