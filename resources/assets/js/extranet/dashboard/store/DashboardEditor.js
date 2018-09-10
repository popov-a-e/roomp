"use strict";

const initialState = {
  restrictions: {
    closed: null,
    closed_to_arrival: null,
    closed_to_departure: null,
    minstay: null,
    maxstay: null,
    minstay_on_arrival: null,
  },
  available: null,
  prices: {},
};

export default {
  namespaced: true,
  state: () => ({
    rate_id: null,
    room_id: null,
    from: null,
    till: null,
    loading: false,
    is_visible: false,
    restrictions: {
      closed: null,
      closed_to_arrival: null,
      closed_to_departure: null,
      minstay: null,
      maxstay: null,
      minstay_on_arrival: null,
    },
    available: null,
    prices: {},

    rangeStart: null,
    rangeEnd: null
  }),
  mutations: {
    setAvailability: (state, value) => state.available = value,
    setPrice: (state, {occupancy, price}) => Vue.set(state.prices, occupancy, price),

    setMinstay: (state, value) => Vue.set(state.restrictions, 'minstay', value),
    setMaxstay: (state, value) => Vue.set(state.restrictions, 'maxstay', value),
    setMinstayOnArrival: (state, value) => Vue.set(state.restrictions, 'minstay_on_arrival', value),
    setClosed: (state, value) => Vue.set(state.restrictions, 'closed', value),
    setClosedToArrival: (state, value) => Vue.set(state.restrictions, 'closed_to_arrival', value),
    setClosedToDeparture: (state, value) => Vue.set(state.restrictions, 'closed_to_departure', value),

    setLoading: (state, loading) => state.loading = loading,

    show: state => state.is_visible = true,
    hide: state => {
      state.restrictions = {
        closed: null,
        closed_to_arrival: null,
        closed_to_departure: null,
        minstay: null,
        maxstay: null,
        minstay_on_arrival: null,
      };
      state.prices = {};
      state.available = null;
      state.from = null;
      state.till = null;
      state.room_id = null;
      state.rate_id = null;

      state.is_visible = false;
    },

    setRoom: (state, room_id) => state.room_id = room_id,
    setRate: (state, rate_id) => state.rate_id = rate_id,

    setRangeStart: (state, date) => {
      state.rangeStart = date;
      state.from = date;
    },
    setRangeEnd: (state, date) => {
      state.rangeEnd = date;
      if (state.rangeEnd > state.rangeStart) {
        state.till = date;
      } else {
        state.till = state.rangeStart;
        state.from = state.rangeEnd;
      }
    }
  },
  actions: {
    save: ({state, getters, commit}, e) => {
      commit('setLoading', true);
      const data = Object.assign({}, state);
      data.prices = getters.prices;

      axios.post('/hotel/update/', data).then(response => {
        commit('setLoading', false);
        commit('DashboardTable/applyEditedValues', {...data, dates: getters.dates}, {root: true});
        commit('hide');
        commit('DashboardTable/changesSavedPopupShow', null, {root: true});

        commit('DashboardTable/setChangesSavedPopupTimeout', setTimeout(() => commit('DashboardTable/changesSavedPopupHide', null, {root: true}), 3000), {root: true});
      });
    },
  },
  getters: {
    guestNumber: (state, getters, rootState, rootGetters) => state.room_id && Array.from({length: rootState.DashboardTable.rooms[state.room_id].max_guest_number}).map((v, i) => i + 1),

    initialRestrictions: (state, getters, rootState, rootGetters) => {
      const dashboard = rootState.DashboardTable;
      const rangeDates = getters.dates;
      let restrictions = null;

      restrictions = dashboard.restrictions && dashboard.restrictions[state.room_id] && dashboard.restrictions[state.room_id][state.rate_id];

      const getIntermediaryValue = key => {
        if (!(restrictions && rangeDates[0])) return null;

        const firstValue = restrictions[rangeDates[0]] && restrictions[rangeDates[0]][key];

        const all = rangeDates.every(date => restrictions[date] && restrictions[date][key] === firstValue);

        return all ? firstValue : null;
      };

      return {
        closed: getIntermediaryValue('closed'),
        closed_to_arrival: getIntermediaryValue('closed_to_arrival'),
        closed_to_departure: getIntermediaryValue('closed_to_departure'),
        minstay: getIntermediaryValue('minstay'),
        maxstay: getIntermediaryValue('maxstay'),
        minstay_on_arrival: getIntermediaryValue('minstay_on_arrival'),
      }
    },
    initialPrices: (state, getters, rootState, rootGetters) => {
      const dashboard = rootState.DashboardTable;
      const rangeDates = getters.dates;
      let prices = {};

      if (!dashboard.rooms || !dashboard.rooms[state.room_id]) return;

      const guestNumber = Array.from({length: dashboard.rooms[state.room_id].max_guest_number}).map((v, i) => i + 1);

      const initialPrices = dashboard.prices && dashboard.prices[state.room_id] && dashboard.prices[state.room_id][state.rate_id];

      guestNumber.forEach(function (occupancy) {
        const pricesOccupancy = initialPrices && initialPrices[occupancy];

        if (!pricesOccupancy) return prices[occupancy] = null;

        const firstValue = pricesOccupancy[rangeDates[0]];
        const all = rangeDates.every(date => pricesOccupancy[date] === firstValue);
        prices[occupancy] = all ? firstValue : null;
      });

      return prices;
    },
    initialAvailability: (state, getters, rootState, rootGetters) => {
      const dashboard = rootState.DashboardTable;
      const rangeDates = getters.dates;

      const firstValue = dashboard.availability && dashboard.availability[state.room_id] && dashboard.availability[state.room_id][rangeDates[0]];

      if (firstValue === null || firstValue === undefined) return null;

      const all = rangeDates.every(date => dashboard.availability[state.room_id][date] === firstValue);

      return all ? firstValue : null;
    },

    available: (state, getters) => state.available === null ? getters.initialAvailability : state.available,
    prices: (state, getters) => {
      let initialPrices = Object.assign({}, getters.initialPrices);
      let prices = Object.assign({}, state.prices);

      const occupancies = Object.keys(initialPrices).reverse();
      let lastOccupancy = null;

      occupancies.forEach(occupancy => {
        const price = prices[occupancy];
        if (price) {

          lastOccupancy = price;
        } else {
          prices[occupancy] = initialPrices[occupancy] || lastOccupancy;
        }
      });
/*
      for (let key in initialPrices) {
        if (!initialPrices.hasOwnProperty(key)) continue;
        dd (key);
        prices[key] = prices[key] === null || prices[key] === undefined ? initialPrices[key] : prices[key];
      }*/

      return prices;
    },
    restrictions: (state, getters) => {
      let restrictions = Object.assign({}, state.restrictions);

      for (let key in restrictions) {
        if (!restrictions.hasOwnProperty(key)) continue;
        restrictions[key] = restrictions[key] === null ? getters.initialRestrictions[key] : restrictions[key];
      }

      return restrictions;
    },

    dates: (state, getters, rootState, rootGetters) => rootGetters['DashboardTable/dates'].filter(date => date >= state.from && date <= state.till),
  }
}