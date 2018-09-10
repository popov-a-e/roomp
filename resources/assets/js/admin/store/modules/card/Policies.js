"use strict";

import mapper from '../../../helpers/mapper';

const MAX_LAST_MINUTE_HOURS = 168;
const MAX_LAST_MINUTE_DISCOUNT = 50;
const MIN_LAST_MINUTE_HOURS = 1;
const MIN_LAST_MINUTE_DISCOUNT = 1;

const store = {
  namespaced: true,
  state: () => ({}),
  mutations: {
    setPolicies: (state, data) => {
      if (!data) return;
      state.bed_number = data.bed_number;
      state.price_infants = data.price_infants;
      state.price_children = data.price_children;
      state.price_adults = data.price_adults;
      state.age_children = data.age_children;
      state.breakfast_price = data.breakfast_price;
      state.last_minute = data.last_minute || {};
    },
    setLastMinuteRule: (state, {discount, hour, prev}) => {
      if (prev) Vue.delete(state.last_minute, prev);
      Vue.set(state.last_minute, hour, discount);
    },
    addRule: (state, {discount, hour}) => {
      Vue.set(state.last_minute, hour, discount);
    },
    removeRoom: (state, hour) => Vue.delete(state.last_minute, hour)
  },
  actions: {
    load ({state, commit}) {
      commit('loadingStateChange', 'downloading');
      axios.get(`/hotels/${state.hotelID}/policy`).then(response => {
        commit('setPolicies', response.data);
        commit('loadingStateChange', 'ready')
      });
    },
    save ({state, commit}) {
      commit('loadingStateChange', 'uploading');
      axios.put(`/hotels/${state.hotelID}/policy`, state).then(response => {
        commit('loadingStateChange', 'success');
        setTimeout(() => commit('loadingStateChange', 'ready'), 1000);
      });
    }
  },
  getters: {
    loadingState: state => {
      switch (state.loading_state) {
        case 'disconnected':
          return 'Нет связи с сервером';
          break;
        case 'success':
          return 'Успешно!';
          break;
        case 'ready':
          return 'Сохранить';
          break;
        case 'downloading':
          return 'Загрузка...';
          break;
        case 'uploading':
          return 'Загрузка данных на сервер';
          break;
      }
    },
    hours: state => Object.keys(state.last_minute).map(Number),
    limits: (state, getters) => getters.hours.reduce((limits, hour, index, hours) => {
      const prevHours = hours[index - 1];
      const nextHours = hours[index + 1];
      let minHours = MIN_LAST_MINUTE_HOURS, minDiscount = MIN_LAST_MINUTE_DISCOUNT;
      let maxHours = MAX_LAST_MINUTE_HOURS, maxDiscount = MAX_LAST_MINUTE_DISCOUNT;

      if (prevHours !== undefined) {
        minHours = prevHours + 1;
        maxDiscount = 1 * state.last_minute[prevHours] - 1;
      }

      if (nextHours !== undefined) {
        maxHours = nextHours - 1;
        minDiscount = 1 * state.last_minute[nextHours] + 1;
      }

      limits[hour] = {hours: {min: minHours, max: maxHours}, discount: {min: minDiscount, max: maxDiscount}};


      return limits;
    }, {}),
    minHour: (state, getters) => getters.hours.reduce((p, c) => Math.max(p, c), 0) + 1,
    maxDiscount: (state, getters) => Object.values(state.last_minute).map(Number).reduce((p, c) => Math.min(p, c), MAX_LAST_MINUTE_DISCOUNT) - 1
  },
};

export default mapper({
  hotelID: 'setHotelID',
  price_infants: null,
  price_children: null,
  price_adults: null,
  breakfast_price: {default: 0},
  last_minute: {default: {}},
  bed_number: {
    default: 0
  },
  age_children: {
    default: 12
  },
  loading_state: {
    default: 'disconnected',
    mutation: 'loadingStateChange'
  }
}, store);