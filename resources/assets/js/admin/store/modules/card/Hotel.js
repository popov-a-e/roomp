"use strict";

import mapper from './../../../helpers/mapper';

const store = {
  namespaced: true,
  mutations: {
    deleteRoom: (state, roomID) => {
      let rooms = state.hotel.rooms;
      const room = state.hotel.rooms.map((v,i) => {v.i = i; return v;}).where('id',Number(roomID));
      rooms.splice(room.i, 1);

      Vue.set(state.hotel, 'rooms', rooms);
    },

    commentEdit: state => state.comment_edited = true,
    commentSave: state => state.comment_edited = false,

    setHotelComment: (state, value) => Vue.set(state.hotel, 'comment', value),
  },
  actions: {
    load ({commit, state}) {
      axios.get('/hotels/'+state.id).then(response => {
        commit('setHotel', response.data);
        commit('setGoodyBagsLeft', response.data.goody_bags_left);
        commit('setGoodyBagsChanged', false);
        commit('setGoodyBagsNeededNextMonth', response.data.goody_bags_needed_next_month);
      });
    },
    updateGoodyBags({commit, state}) {
      axios.post('/hotels/' + state.id + '/update_goody_bags', {n: state.goody_bags_left}).then(() => {
        commit('setGoodyBagsChanged', false);
      }).catch(() => alert('Ошибка'));
    },
    saveHotelComment({commit, state}) {
      axios.post('/hotels/' + state.id + '/comment', {comment: state.hotel.comment}).then(() => {
        commit('commentSave');
      });
    }
  }
};

export default mapper({
  hotel: null,
  id: 'setID',
  goody_bags_left: {
    default: 0,
    function: (state, value) => {
      state.goody_bags_left = value;
      state.goody_bags_changed = true;
    }
  },
  goody_bags_needed_next_month: null,
  goody_bags_changed: null,

  comment_edited: {default: false}
}, store);