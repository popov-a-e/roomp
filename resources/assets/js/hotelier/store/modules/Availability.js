"use strict";

export default {
  namespaced: true,
  state: {
    from: new Date(Math.floor(+new Date() / 86400 / 1000) * 86400 * 1000),
    till: new Date(Math.floor(+new Date() / 86400 / 1000 + 30) * 86400 * 1000),
    availability: {},
    savedAvailability: {},
    edited: false
  },
  mutations: {
    setFrom: (state, value) => state.from = value,
    setTill: (state, value) => state.till = value,

    setAvailability: (state, data) => {
      state.edited = false;
      state.savedAvailability = Object.assign({}, data);
      state.availability = Object.assign({}, data);
    },

    setDay: (state, payload) => {
      state.edited = true;
      let avail = Object.assign({}, state.availability[payload.room]);
      avail[payload.date.toISODateString()] = isNaN(parseInt(payload.value)) ? 0 : parseInt(payload.value);
      Vue.set(state.availability, payload.room, avail);
    },

    cancel: state => {
      state.edited = false;
      state.availability = Object.assign({}, state.savedAvailability)
    }
  },
  actions: {
    load({state, commit}) {
      if (!state.till) return;

      axios.get('/availability/', {
        params: {
          from: state.from.toISODateString(),
          till: state.till.toISODateString()
        }
      }).then(response => {
        commit('setAvailability', response.data);
      });
    },

    update({state, commit, dispatch, getters}) {
      axios.post('/availability/', {
        data: getters.availArray,
        from: state.from.toISODateString(),
        till: state.till.toISODateString()
      }).then(response => {
        dispatch('load');
      });
    }
  },
  getters: {
    dates: state => {
      let dates = [];
      if (!state.till) return [];
      const from = +new Date(state.from.getFullYear(), state.from.getMonth(), state.from.getDate(), 0, 0, 0, 0);
      const till = +new Date(state.till.getFullYear(), state.till.getMonth(), state.till.getDate(), 0, 0, 0, 0);

      for (let i = from; i <= till; i+= 86400000) {
        dates.push(new Date(i));
      }

      return dates;
    },
    rooms: state => {
      let result = [];

      return state.availability;
    },
    availArray: state => {
      let arr = Object.assign({}, state.availability);

      for(let room in arr) {
        if (!arr.hasOwnProperty(room)) continue;
        let a = [];

        for(let date in arr[room]) {
          if (!arr[room].hasOwnProperty(date)) continue;
          a.push({date, available: arr[room][date]});
        }

        arr[room] = a;
      }

      return arr;
    }

  }
}
