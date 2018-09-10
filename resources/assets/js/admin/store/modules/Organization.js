"use strict";

import mapper from '../../helpers/mapper';

const store = {
  mutations: {
    cancel: state => {
      for (let key in state.previous) {
        if (!state.previous.hasOwnProperty(key)) continue;

        state[key] = data[key];
      }
    },
    setRequsities(state, data) {
      for (let key in state) {
        if (!state.hasOwnProperty(key) || key === 'hotelID' || key === 'previous' || !data.hasOwnProperty(key)) continue;

        state[key] = data[key];

        state.previous[key] = data[key];
      }
    },
    setOrganization: (state, data) => {
      state.organization = data;
      state.previous = data;
    },
    setOrganizationField: (state, {field, value}) => Vue.set(state.organization, field, value),

    resetLocaleChanges: state => {
      state.language = null;
      state.country = null;
      state.currency = null;
    },
    toggleOrganizations: state => state.organizationsVisible = !state.organizationsVisible,
    hideOrganizations: state => state.organizationsVisible = false,
  },
  actions: {
    load ({state, commit}) {
      axios.get(`/hotels/${state.hotelID}/organization`).then(response => {
        commit('setOrganization', response.data.org);
        commit('setOrganizationOriginal', response.data.organization);
        commit('setHotel', filterObject(response.data, (value, key) => !(value instanceof Object)));
        commit('setHotelier', response.data.hotelier);
      });
    },
    setExistingOrganization({state, commit}, {id}) {
      axios.get(`/organizations/${id}`).then(response => {
        commit('setOrganization', response.data.org);
        commit('setOrganizationOriginal', response.data.organization);
        commit('hideOrganizations');
      })
    },
    apply ({state, getters, commit, dispatch}) {
      //commit('setCEOShortName', state.CEO_short_name || getters.CEO_short_name_auto);
      commit('setLoadStatus', 'loading');
      axios.put(`/hotels/${state.hotelID}/organization/${state.organization_original.id}`, state.organization)
        .then(response => {
          dispatch('load');
          commit('setLoadStatus', 'success');
          setTimeout(() => {
            commit('setLoadStatus', null);
          }, 1500);
        })
        .catch(response => {
          commit('setLoadStatus', 'error');
        });
    },

    saveLocaleChanges({state, commit, dispatch}) {
      axios.put(`/hotels/${state.hotelID}/organization/${state.organization_original.id}/locales`, only(state, ['currency', 'language', 'country']))
        .then(response => {
          dispatch('load');
          commit('resetLocaleChanges');
        });
    }
  },
  getters: {
    loadStatusString: state => {
      switch (state.load_status) {
        case 'loading':
          return 'Загрузка...';
        case 'success':
          return 'Успешно';
        case 'error':
          return 'Ошибка';
        default:
          return 'Сохранить';
      }
    },

    CEO_short_name_auto: state => {
      const CEO = state.organization && state.organization.CEO;

      if (!CEO) return '';

      let name_pieces = CEO.trim().split(' ');

      if (name_pieces.length < 2) return '';

      name_pieces[name_pieces.length - 1] = name_pieces[name_pieces.length - 1][0] + '.';

      if (name_pieces.length >= 3) name_pieces[name_pieces.length - 2] = name_pieces[name_pieces.length - 2][0] + '.';

      return name_pieces.join(' ');
    },
    language: state => state.language ? state.language : state.hotelier ? state.hotelier.locale : null,
    country: state => state.country ? state.country : state.organization_original ? state.organization_original.locale : null,
    currency: state => state.currency ? state.currency : state.hotel ? state.hotel.currency : null,

    hasLocaleChanges: state => state.language || state.country || state.currency,
  }
};

export default mapper({
  hotelID: 'setHotelID',

  language: null,
  country: null,
  currency: null,

  previous: {default: {}},
  load_status: null,

  organization: null,
  hotel: null,
  hotelier: null,
  organization_original: null,
  organizationsVisible: {default: false}
}, store);