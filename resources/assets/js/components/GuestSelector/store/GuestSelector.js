"use strict";

import mapper from '../../../admin/helpers/mapper';

export default mapper({
  adult_number: {
    default: 2,
    function: (state, value) => {
      state.adult_number = value;
      state.room_number = Math.min(state.adult_number, Math.max(state.room_number, Math.ceil(state.adult_number / 4)));
    }
  },
  children_number: {
    default: 0,
    function: (state, value) => {
      state.children_number = value;

      if (value === 0) return state.children = [];

      const len = state.children.length;

      if (len > value) {
        for (let i = 0; i < len - value; ++i) {
          state.children.pop();
        }
      }

      if (len < value) {
        for (let i = 0; i < value - len; ++i) {
          state.children.push(0);
        }
      }
    }
  },
  room_number: {
    default: 1,
    function: (state, value) => {
      state.room_number = value;
      state.adult_number = Math.min(state.room_number * 4, Math.max(state.room_number, state.adult_number));
    }
  },
  children: {
    default: [],
    function: (state, {index, value}) => {
      Vue.set(state.children, index, value);
    }
  },
  guest_selector_visible: null
},{
  mutations: {
    toggleGuestSelector: state => state.guest_selector_visible = !state.guest_selector_visible,
    hideGuestSelector: state => state.guest_selector_visible = false,
    seedChildren: (state, array) => state.children = array,
  },
  getters: {
    maxRoomNumber: state => 6,
    minRoomNumber: state => 1,
    agesList: state => Array.from({length: 17}).map((v, i) => window.pluralize("common.years", i)),
  }
});