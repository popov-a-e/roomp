'use strict';

export default {
  namespaced: true,
  state() {
    let from = new Date();

    from.setHours(0, 0, 0, 0);
    let till = new Date(+from);
    till.setDate(till.getDate() + 30);

    return {
      rooms: {},
      rates: [],

      availability: null,
      prices: null,
      restrictions: null,

      ratesAppearance: {},

      from, till,

      scrollTop: 0,
      scrollLeft: 0,

      changesSavedPopupVisible: false,
      changesSavedPopupTimeout: null,

      dashboardIsVerticallyScrollable: false,
    }
  },
  mutations: {
    setRooms: (state, value) => state.rooms = value,
    setRates: (state, data) => state.rates = data,


    setAvailability: (state, value) => state.availability = value,
    setPrices: (state, data) => state.prices = Object.assign({}, data),
    setRestrictions: (state, data) => state.restrictions = data,

    changeRatesAppearance: (state, {roomID, rateID, rateVisible}) => {
      if (!state.ratesAppearance[roomID]) Vue.set(state.ratesAppearance, roomID, {});
      Vue.set(state.ratesAppearance[roomID], rateID, !rateVisible);
    },

    setDates: (state, {from, till}) => {
      state.from = from;
      state.till = till;
    },

    scrollChange: (state, e) => {
      state.scrollTop = e.target.scrollTop;
      state.scrollLeft = e.target.scrollLeft;
    },

    updateScrollState: state => {
      state.dashboardIsVerticallyScrollable = document.querySelector('.dashboard-wrapper-content').getBoundingClientRect().height - document.querySelector('.dashboard-wrapper-content').clientHeight

      //state.dashboardIsVerticallyScrollable = document.querySelector('.dashboard-wrapper-content').scrollWidth - document.querySelector('.dashboard-wrapper-content').getBoundingClientRect().width > 0;
    },

    applyEditedValues: (state, {room_id, rate_id, available, restrictions, prices, dates}) => {
      dates.forEach(date => {
        if (available !== null) Vue.set(state.availability[room_id], date, available);
        for (let key in restrictions) {
          if (!restrictions.hasOwnProperty(key)) continue;
          if (!state.restrictions[room_id]) Vue.set(state.restrictions, room_id, {});
          if (!state.restrictions[room_id][rate_id]) Vue.set(state.restrictions[room_id], rate_id, {});
          if (!state.restrictions[room_id][rate_id][date]) Vue.set(state.restrictions[room_id][rate_id], date, {});

          if (restrictions[key] !== null) Vue.set(state.restrictions[room_id][rate_id][date], key, restrictions[key]);
          if (key === 'minstay' && restrictions[key] < 2 && restrictions[key] !== null) {
            Vue.set(state.restrictions[room_id][rate_id][date], key, null);
          }
        }
        for (let occupancy in prices) {
          if (!prices.hasOwnProperty(occupancy)) continue;
          if (!state.prices[room_id]) Vue.set(state.prices, room_id, {});
          if (!state.prices[room_id][rate_id]) Vue.set(state.prices[room_id], rate_id, {});
          if (!state.prices[room_id][rate_id][occupancy]) Vue.set(state.prices[room_id][rate_id], occupancy, {});
          if (prices[occupancy]) Vue.set(state.prices[room_id][rate_id][occupancy], date, prices[occupancy]);
        }
      });
    },

    changesSavedPopupShow: state => state.changesSavedPopupVisible = true,
    changesSavedPopupHide: state => {
      clearTimeout(state.changesSavedPopupTimeout);
      state.changesSavedPopupVisible = false;
    },
    setChangesSavedPopupTimeout: (state, timeout) => state.changesSavedPopupTimeout = timeout,
  },

  actions: {
    load({state, dispatch, commit}) {
      if (!state.from || !state.till) return;

      const params = {
        from: state.from.toISODateString(),
        till: state.till.toISODateString()
      };

      axios.get('/prices', {params}).then(response => commit('setPrices', response.data));
      axios.get('/restrictions', {params}).then(response => commit('setRestrictions', response.data));
      axios.get('/availability', {params}).then(response => commit('setAvailability', response.data));
    },

    getRooms ({rootState, commit}) {
      axios.get('/rooms')
        .then(response => {
          const rooms = {};
          response.data.forEach(room => rooms[room.id] = room);

          commit('setRooms', rooms);
        });
    },
    getRates({commit}) {
      axios.get('/rates').then(response => {
        commit('setRates', response.data.filter(rate => rate.id < 4));
      });
    },
  },

  getters: {
    dates: state => {
      let dates = [];
      const from = state.from;
      const till = state.till;
      const nights = (till - from) / 86400000 + 1;

      return Array.from({length: nights}, (v, i) => {
        return (new Date(+from + i * 86400000)).toISODateString()
      });
    },
    months: (state, getters) => {
      return getters.dates.reduce((months, date) => {
        const monthName = new Date(date).getRussianMonth(true) + ' ' + new Date(date).getFullYear();

        if (!months[monthName]) months[monthName] = 0;

        months[monthName]++;

        return months;
      }, {})
    },
    ratesVisibility: state => {
      let visibility = {};

      for (let room in state.rooms) {
        if (!state.rooms.hasOwnProperty(room)) continue;

        visibility[room] = {};

        state.rates.forEach((rate, i) => {
          const appearance = state.ratesAppearance && state.ratesAppearance[room] && state.ratesAppearance[room][rate.id];

          visibility[room][rate.id] = appearance === null || appearance === undefined ? i === 0 : appearance;
        });
      }

      return visibility;
    },
    state: state => JSON.stringify(state),
  }
}