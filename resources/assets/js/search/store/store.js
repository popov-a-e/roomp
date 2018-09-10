"use strict";

import {serializeArray} from './../../helpers';

import Filters from './modules/Filters';
import Appearance from './modules/Appearance';
import BackendData from './modules/BackendData';
import Header from './../../components/RoompHeader/store/Header';
import GuestSelector from '../../components/GuestSelector/store/GuestSelector';

import Bus from '../../Bus';

import Animator from './plugins/Animator';
import overlayPlugin from '../../plugins/overlayPlugin';

export default new Vuex.Store({
  root: true,
  state: {
    hotels: [],
    link: '',
    sortBy: 1,
    photos: []
  },
  mutations: {
    newHotels: (state, value) => state.hotels = value,
    updateLink (state, getters) {
      let link = window.location.href.split('?')[0];
      const filters = Object.assign({}, except(state.Filters, ['maxPrice']), state.GuestSelector);

      link = link + '?' + serializeArray(filters);

      window.history.pushState(new Date(), window.document.title, link);

      state.link = link;
    },
    changeSort (state, sortBy) {
      state.sortBy = sortBy;
    }
  },
  getters: {
    filters: state => Object.assign({}, state.GuestSelector, state.Filters),
    cityData: state => state.BackendData.cities.where('id', state.Filters.city),
    sortParam: state => state.BackendData.sortParams.where('id', state.sortBy),
    hotelsSorted: (state, getters) => {
      const direction = getters.sortParam.sort_dir === 'desc' ? 1 : -1;
      let param;

      switch (getters.sortParam.sort_by) {
        case 'distance':
          param = (hotel) => {
            const city = state.BackendData.cities.where('id', hotel.city);

            const latD = city.lat - hotel.lat;
            const lngD = city.lng - hotel.lng;

            return Math.sqrt(lngD * lngD + latD * latD);
          };

          break;

        default:
          param = (hotel) => hotel.price;
          break;
      }

      return state.hotels.sort((a, b) => {
        const aParam = param(a);
        const bParam = param(b);

        if (a.count > 0 && b.count === 0) return -1;
        if (a.count === 0 && b.count > 0) return 1;

        return aParam > bParam ? direction : aParam === b.param ? 0 : -direction;
      });
    }
  },
  actions: {
    loadHotels({commit, state}) {
      if (!state.Filters.from || !state.Filters.till) return;

      let query = {};
      for (let key in state.Filters) {
        if (state.Filters[key] !== undefined && state.Filters.hasOwnProperty(key)) {
          query[key] = state.Filters[key];
        }
      }

      query.children = state.GuestSelector.children;
      query.adult_number = state.GuestSelector.adult_number;
      query.room_number = state.GuestSelector.room_number;

      let promise = axios.post(
        '/hotels', query
      );

      promise.then((response) => {
        commit('newHotels', response.data);
      });
    }
  },
  modules: {
    Appearance, Filters, BackendData, Header,
    GuestSelector: GuestSelector.store
  },
  plugins: [Animator, overlayPlugin]
});