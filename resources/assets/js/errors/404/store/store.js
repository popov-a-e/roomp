"use strict";

import Header from './../../../components/RoompHeader/store/Header';
import Footer from './../../../components/RoompFooter/store/Footer';

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

import overlayPlugin from '../../../plugins/overlayPlugin';

export default new Vuex.Store({
  state: {
    cityID: null,
    from: null,
    till: null,
    roomNumber: 1,
    guestNumber: 1,
    cities: [],
    roomNumbers: __('common.room_list').map((v,i) => {return {id: i + 1, name: v}}),
    guestNumbers: __('common.guest_list').map((v,i) => {return {id: i + 1, name: v}}),
    footerVisible: false
  },

  mutations: {
    initialize: (state, params) => {
      state.cities = params.cities;

      let d = new Date();
      d.setHours(0, 0, 0, 0);
      d.setDate(d.getDate() + 1);
      state.from = new Date(d.getTime());
      d.setDate(d.getDate() + 1);
      state.till = new Date(d.getTime());

      state.cityID = state.cities[0].id;
    },
    setTill: (state, value) => state.till = value,
    setFrom: (state, value) => state.from = value,
    setRoomNumber: (state, value) => state.roomNumber = value,
    setGuestNumber: (state, value) => state.guestNumber = value,
    setCity: (state, value) => state.cityID = value,
    footerToggle: state => state.footerVisible = !state.footerVisible
  },

  actions: {
    confirm ({state}) {
      window.location.href = `/search?city=${state.cityID}&from=${state.from.toISODateString()}&till=${state.till.toISODateString()}&room_number=${state.roomNumber}&guest_number=${state.guestNumber}`;
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
    roomNumbersBounded (state, getters, rootState, rootGetters) {
      const roomsMax = state.guestNumber;
      const roomsMin = Math.ceil(roomsMax / 3);

      return rootState.roomNumbers.filter(number => number.id >= roomsMin && number.id <= roomsMax);
    },

    footerTop: state => {
      /*if (state.footerVisible) {
        let height = document.querySelector('footer').scrollHeight - 20;
        return `calc(100% - ${height}px)`;
      }*/

      return 'calc(100% + 20px)';
    }
  },

  modules: {
    Header, Footer
  },

  plugins: [Scroll, overlayPlugin]
});