"use strict";

const defaults = {
  id: null,
  name: '',
  size: 0,
  max_guest_number: 2,
  number: 1,
  allotments: [],
  room_class_id: 1
};

export default {
  namespaced: true,
  state() {
    return Object.assign({}, defaults);
  },

  mutations: {
    setID: (state, value) => state.id = value,
    setName: (state, value) => state.name = value,
    setSize: (state, value) => state.size = value,
    setMaxGuestNumber: (state, value) => state.max_guest_number = value,
    setNumber: (state, value) => state.number = value,
    setRoomClass: (state, value) => state.room_class_id = value.key,
    setAllotment: (state, value) => {
      let allotments = state.allotments;
      const index = allotments.indexOf(value.key);

      if (value.value) {
        if (index >= 0) return;

        allotments.push(value.key);
      } else {
        if (index === -1) return;

        allotments.splice(index, 1);
      }

      state.allotments = allotments;
    },
    setRoom: (state, value) => {
      for (const key in defaults) {
        if (!defaults.hasOwnProperty(key)) continue;
        state[key] = (value && value[key]) || defaults[key];
      }

      state.allotments = state.allotments.map(a => a);
    }
  },

  actions: {
    save ({commit, state, rootState}) {
      axios.post('/room/' + (state.id || ''), {
        ...state,
        hotel_id: rootState.Hotel.hotel.id
      }).then(response => {
        commit('Hotel/Rooms/updateRoom', response.data, {root: true});
        commit('Hotel/Rooms/selectRoom', null, {root: true});
      });
    },
    del({commit, state}) {
      if (!confirm('Вы уверены?')) return;
      axios.delete('/room/' + state.id)
        .then(response => {
          commit('Hotel/Rooms/deleteRoom', state.id, {root: true});
          commit('Hotel/Rooms/selectRoom', null, {root: true});
        });
    }
  }
};
