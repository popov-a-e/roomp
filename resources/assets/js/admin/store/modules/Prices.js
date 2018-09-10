"use strict";

import mapper from '../../helpers/mapper';

const store = {
  namespaced: true,
  state: {
    from: new Date(Math.floor(+new Date() / 86400 / 1000) * 86400 * 1000),
    till: new Date(Math.floor(+new Date() / 86400 / 1000 + 30) * 86400 * 1000),
    prices: {},
    savedPrices: {},
    edited: false,
    hotelID: null,
    channel: false,
  },
  mutations: {
    setHotelID: (state, id) => state.hotelID = id,

    setFrom: (state, value) => state.from = value,
    setTill: (state, value) => state.till = value,

    setPrices: (state, data) => {
      state.edited = false;
      state.savedPrices = Object.assign({}, data);
      state.prices = Object.assign({}, data);
    },

    setDay: (state, payload) => {
      state.edited = true;
      let avail = Object.assign({}, state.prices[payload.room]);
      avail[payload.date.toISODateString()] = isNaN(parseInt(payload.value)) ? 0 : parseInt(payload.value);
      Vue.set(state.prices, payload.room, avail);
    },

    setChannel: (state, value) => state.channel = value,

    cancel: state => {
      state.edited = false;
      state.prices = Object.assign({}, state.savedPrices)
    }
  },
  actions: {
    load({state, commit}) {
      if (!state.till) return;

      const url = `/hotels/${state.hotelID}/${state.channel ? 'channel_' : ''}prices`;
      axios.get(url, {
        params: {
          from: state.from.toISODateString(),
          till: state.till.toISODateString(),
          rate_id: state.rate_id
        }
      }).then(response => {
        commit('setPrices', response.data);
      });
    },
    loadRates({state, commit}) {
      axios.get(`/hotels/${state.hotelID}/prices`).then(response => {
        commit('setRates', response.data);
      });
    },
    getRooms ({state, commit}) {
      axios.get(`/hotels/${state.hotelID}/rooms/`)
        .then(response => {
          const rooms = {};

          response.data.forEach(room => rooms[room.id] = room.name);

          commit('setRooms', rooms);
        });
    }
  },
  getters: {
    dates: state => {
      let dates = [];
      if (!state.till || !state.from) return [];
      const from = +new Date(state.from.getFullYear(), state.from.getMonth(), state.from.getDate(), 0, 0, 0, 0);
      const till = +new Date(state.till.getFullYear(), state.till.getMonth(), state.till.getDate(), 0, 0, 0, 0);

      for (let i = from; i <= till; i += 86400000) {
        dates.push(new Date(i));
      }

      return dates;
    },
    priceArray: state => {
      let arr = Object.assign({}, state.prices);

      for(let room in arr) {
        if (!arr.hasOwnProperty(room)) continue;
        let a = [];

        for(let date in arr[room]) {
          if (!arr[room].hasOwnProperty(date)) continue;
          a.push({date, price: arr[room][date]});
        }

        arr[room] = a;
      }

      return arr;
    }
  }
};

export default mapper({rooms: null, rates: null, rate_id: {default: 1}},store);