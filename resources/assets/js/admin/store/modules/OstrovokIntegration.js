"use strict";

import mapper from '../../helpers/mapper';

const store = {
  state() {
    return {}
  },
  mutations: {
    setMappingRoom (state, payload) {
      Vue.set(state.mapping, payload.ostrovok_id, payload.roomp_id);
    }
  },
  actions: {
    load ({state, commit}) {
      axios.get(`/hotels/${state.hotel_id}/channels/ostrovok`).then(response => {
        commit('setOstrovokID', response.data.hotel_id);
        commit('setOstrovokOriginalID', response.data.hotel_id);
        commit('setOstrovokRooms', response.data.rooms);
        commit('setRoompRooms', response.data.roomp_rooms);

        const map = {};

        response.data.rooms.forEach(mapping => {
          map[mapping.ostrovok_id] = mapping.room_id;
        });

        commit('setMapping', map);
      });
    },
    saveHotel({state, commit, dispatch}) {
      axios.post(`/hotels/${state.hotel_id}/channels/ostrovok/${state.ostrovok_id}`).then(response => {
        dispatch('load');
      });
    },
    setRooms({state, commit, dispatch}) {
      commit('setRatesUploadStatus', 'loading');
      axios.post(`/hotels/${state.hotel_id}/channels/ostrovok`, {
        map: state.mapping
      }).then(response => {
        dispatch('load');
        commit('setRatesUploadStatus', 'success');
        setTimeout(() => commit('setRatesUploadStatus', null), 1500);
      }).catch(response => commit('setRatesUploadStatus', 'error'));
    },
    deleteOstrovokConnection ({state, dispatch}) {
      axios.post(`/channels/ostrovok/${state.hotel_id}`, {ostrovok_hotel_id: null}).then(response => {
        dispatch('load');

      });
    }
  },
  getters: {
    roomUploadStatus: state => {
      switch (state.rates_upload_status) {
        case 'loading': return 'Загрузка...';
        case 'success': return 'Успешно!';
        case 'error': return 'Ошибка';
        default: return 'Подтвердить сопоставление';
      }
    },
    ostrovokIDChanged: state => state.ostrovok_id !== state.ostrovok_original_id
  }
};

export default mapper({
  hotel_id: 'setHotelID',
  ostrovok_id: 'setOstrovokID',
  ostrovok_original_id: 'setOstrovokOriginalID',
  mapping: null,
  ostrovok_rooms: null,
  roomp_rooms: null,
  rates_upload_status: null,
}, store)