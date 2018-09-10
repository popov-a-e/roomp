"use strict";

import Header from './../../components/RoompHeader/store/Header';
import Footer from './../../components/RoompFooter/store/Footer';
import GuestSelector from '../../components/GuestSelector/store/GuestSelector';

import {serializeArray} from '../../helpers';

const scrollTo = (from, to) => {
  const main = document.querySelector('.content');

  const delta = (to - from) / 20;

  for (let i = from; from < to ? i < to + 1 : i > to - 1; i+= delta) {
    const t = Math.ceil(i);
    setTimeout(() => {
      main.scrollTop = t;
    }, Math.abs(i - from));
  }
};

const Scroll = store => {
  store.subscribe((mutation, state) => {
    if (mutation.type === 'footerToggle') {
      const footer = document.querySelector('footer');
      const content = document.querySelector('.content');
      if (state.footerVisible) scrollTo(0, content.scrollHeight - content.offsetHeight);
      else scrollTo(content.scrollHeight - content.offsetHeight, 0);
    }
  })
};

import overlayPlugin from '../../plugins/overlayPlugin';

export default new Vuex.Store({
  state: {
    cityID: null,
    from: null,
    till: null,
    cities: [],
    footerVisible: false,

    guest_selector_visible: false
  },

  mutations: {
    initialize: (state, params) => {
      state.cities = params.cities;

      let d = new Date();
      d.setHours(0, 0, 0, 0);
      d.setDate(d.getDate());
      state.from = new Date(d.getTime());
      d.setDate(d.getDate() + 1);
      state.till = new Date(d.getTime());

      state.cityID = state.cities[0].id;
    },
    setTill: (state, value) => {
      state.till = value;
    },
    setFrom: (state, value) => {
      state.from = value;
    },
    setCity: (state, value) => {
      state.cityID = value;
    },

    footerToggle: state => state.footerVisible = !state.footerVisible
  },

  actions: {
    confirm ({state}) {
      const urlString = serializeArray({
        city: state.cityID,
        from: state.from,
        till: state.till,
        room_number: state.GuestSelector.room_number,
        adult_number: state.GuestSelector.adult_number,
        children: state.GuestSelector.children
      });

      window.location.href = `/search?${urlString}`;
    }
  },

  getters: {
    fromFormatted: state => state.from ? state.from.toVerboseDateString() : __('common.checkin'),
    tillFormatted: state => state.till ? state.till.toVerboseDateString() : __('common.checkout'),
    fromDayOfWeek: state => state.from ? state.from.dayOfWeek() : '',
    tillDayOfWeek: state => state.till ? state.till.dayOfWeek() : '',
    nightsFormatted: state => {
      if (!state.till || !state.from) return '';

      state.from.setHours(0, 0, 0, 0);
      state.till.setHours(0, 0, 0, 0);

      const nights = (state.till.getTime() - state.from.getTime()) / 86400000;

      return pluralize('common.nights', nights);
    },
  },

  modules: {
    Header, Footer,
    GuestSelector: GuestSelector.store
  },

  plugins: [Scroll, overlayPlugin]
});