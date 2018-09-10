"use strict";

export default {
  namespaced: true,
  state: {
    cities: [],
    roomNumbers: __('common.room_list').map((v,i) => {return {id: i + 1, name: v}}),
    guestNumbers: __('common.guest_list').map((v,i) => {return {id: i + 1, name: v}}),
    allotments: [],
    roomAmenities: [],
    hotelAmenities: [],
    sortParams: []
  },
  mutations: {
    initialize (state, params) {
      for (let key in params)
        if (params.hasOwnProperty(key))
          state[key] = params[key];

    },
  },
  getters: {
    roomNumbersBounded (state, getters, rootState, rootGetters) {
      const roomsMax = rootState.Filters.guest_number;
      const roomsMin = Math.ceil(roomsMax / 3);

      return rootState.BackendData.roomNumbers.filter(number => number.id >= roomsMin && number.id <= roomsMax);
    }
  }
}