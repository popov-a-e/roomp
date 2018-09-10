"use strict";

import {serializeArray} from './../../../helpers';

import Filters from './../../../search/store/modules/Filters';
import Appearance from './modules/Appearance';
import BackendData from './../../../search/store/modules/BackendData';
import Header from './../../../components/RoompHeader/store/Header';
import Datepicker from './../../components/Datepicker/store';
import overlayPlugin from '../../../plugins/overlayPlugin';
import GuestSelector from '../../../components/GuestSelector/store/GuestSelector';

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
      const filters = Object.assign({}, state.Filters, state.GuestSelector);

      link = link + '?' + serializeArray(filters);

      window.history.pushState(new Date(), window.document.title, link);

      state.link = link;
    },
    changeSort (state, sortBy) {
      state.sortBy = sortBy;
    },
    enableDatepicker: (state, {from, till, direction, resolve}) => {
      state.Datepicker.from = new Date(from.getFullYear(), from.getMonth(), from.getDate(), 0, 0, 0, 0);
      state.Datepicker.till = till ? new Date(till.getFullYear(), till.getMonth(), till.getDate(), 0, 0, 0, 0) : null;
      state.Datepicker.resolve = resolve;
      state.Datepicker.direction = direction;
      state.Appearance.datepickerVisible = true;
      window.overlayCount ++;
    }
  },
  getters: {
    filters: state => Object.assign({}, state.Filters, state.GuestSelector),
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
    loadHotels({commit, state, getters}) {
      if (!state.Filters.from || !state.Filters.till) return;

      let query = {};
      const filters = getters.filters;
      for (let key in filters) {
        if (filters[key] !== undefined && filters.hasOwnProperty(key)) {
          query[key] = filters[key];
        }
      }

      let promise = axios.post(
        '/hotels', query
      );

      promise.then((response) => {
        commit('newHotels', response.data);
      });
    }
  },
  modules: {
    Appearance, Filters, BackendData, Header, Datepicker,
    GuestSelector: GuestSelector.store
  },

  plugins: [overlayPlugin]
});