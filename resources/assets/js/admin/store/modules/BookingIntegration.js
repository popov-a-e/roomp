"use strict";

import mapper from '../../helpers/mapper';

const store = {
  state() {
    return {}
  },
  mutations: {
    setMappingRoom (state, payload) {
      Vue.set(state.mapping, payload.booking, payload.wubook);
    },
    setMappingRate (state, payload) {
      dd (payload);

      const key = Number(payload.booking);
      const val = Number(payload.wubook);

      let el = state.rate_mapping[key] || {};

      el.pplan = val;

      Vue.set(state.rate_mapping, Number(payload.booking), el);
    },
    setMappingRatePlan (state, payload) {
      const key = Number(payload.booking);
      const val = Number(payload.wubook);

      let el = state.rate_mapping[key] || {};

      el.rplan = val;

      Vue.set(state.rate_mapping, Number(payload.booking), el);
    },
    updatePremiumProgram: state => {
      if (!state.connection) {
        state.connection = {
        }
      }

      Vue.set(state.connection, 'premium_program', state.is_premium);
    }
  },
  actions: {
    load ({state, commit}) {
      axios.get(`/hotels/${state.hotel_id}/channels/booking/`).then(response => {
        if (response.data !== false) {
          commit('setConnection', response.data);
          commit('setChannelID', response.data.chid);
          commit('setBookingID', response.data.booking_hotel_id);
          commit('setIsPremium', response.data.premium_program);
          if (response.data.channel_confirmed) commit('setActivationConfirmed', true);
          if (response.data.booking_hotel_id) commit('setProcedureStarted', true);
          if (response.data.mappings.length > 0) commit('setChannelInited', true);
          commit('setBookingRooms', response.data.mappings);
          commit('setBookingRates', response.data.rates);
          commit('setActive', !!response.data.last_active);

          const map = {};
          const rate_mapping = {};

          response.data.mappings.forEach(mapping => {
            map[mapping.booking_room_id] = mapping.wubook_room_id;
          });

          response.data.rates.forEach(mapping => {
            rate_mapping[mapping.booking_rate_id] = {pplan: mapping.wubook_rate_id, rplan: mapping.wubook_rate_plan_id};
          });

          commit('setMapping', map);
          commit('setRateMapping', rate_mapping);
        }
      });
    },
    loadWubookRooms({state, commit}) {
      axios.get(`/hotels/${state.hotel_id}/channels/booking/rooms`).then(response => commit('setWubookRooms', response.data));
    },
    loadWubookRates({state, commit}) {
      axios.get(`/hotels/${state.hotel_id}/channels/booking/rates`).then(response => {
        commit('setWubookRates', response.data.pplans);
        commit('setWubookRatePlans', response.data.rplans);
      });
    },
    syncWubookRooms({state, commit}) {
      axios.get(`/hotels/${state.hotel_id}/channels/booking/rooms/upload`).then(response => commit('setWubookRooms', response.data));
    },
    createOTA({state, commit}) {
      axios.post(`/hotels/${state.hotel_id}/channels/booking/`).then(({data}) => commit('setChannelID', data));
    },
    startProcedure({state, commit}) {
      axios.post(`/hotels/${state.hotel_id}/channels/booking/start/${state.booking_id}`).then(() => commit('setProcedureStarted', true));
    },
    confirmActivation({state, commit}) {
      axios.get(`/hotels/${state.hotel_id}/channels/booking/confirm`).then(response => {
        if (response.data === true) commit('setActivationConfirmed', true);
        else alert("Необходимо активировать канал со стороны Booking.com и дождаться письма от WuBook");
      });
    },
    initChannel({state, commit, dispatch}) {
      axios.get(`/hotels/${state.hotel_id}/channels/booking/init`).then(response => {
        commit('setChannelInited', true);
        dispatch('load');
      });
    },
    syncRoomsRates({state, dispatch}) {
      axios.get(`/hotels/${state.hotel_id}/channels/booking/sync`).then(response => {
        dispatch('load');
      });
    },
    setRates({state, commit, dispatch}) {
      commit('setRateUploadStatus', 'loading');
      axios.post(`/hotels/${state.hotel_id}/channels/booking/set_rate_mapping`, {
        hotel_id: state.hotel_id,
        map: state.rate_mapping
      }).then(response => {
        commit('setRateUploadStatus', 'success');
        setTimeout(() => commit('setRateUploadStatus', null), 1500);
        dispatch('load');
      }).catch(response => commit('setRateUploadStatus', 'error'));
    },
    setRooms({state, commit, dispatch}) {
      commit('setRoomUploadStatus', 'loading');
      axios.post(`/hotels/${state.hotel_id}/channels/booking/set_room_mapping`, {
        hotel_id: state.hotel_id,
        map: state.mapping
      }).then(response => {
        commit('setRoomUploadStatus', 'success');
        setTimeout(() => commit('setRoomUploadStatus', null), 1500);
        dispatch('load');
      }).catch(response => commit('setRoomUploadStatus', 'error'));
    },
    setPremiumProgram({state, commit}) {
      axios.post(`/hotels/${state.hotel_id}/channels/booking/premium_program`,  {
        is_premium: state.is_premium
      }).then(response => commit('updatePremiumProgram'));
    }
  },
  getters: {
    roomUploadStatus: state => {
      switch (state.room_upload_status) {
        case 'loading': return 'Загрузка...';
        case 'success': return 'Успешно!';
        case 'error': return 'Ошибка';
        default: return 'Подтвердить сопоставление комнат';
      }
    },
    rateUploadStatus: state => {
      switch (state.rate_upload_status) {
        case 'loading': return 'Загрузка...';
        case 'success': return 'Успешно!';
        case 'error': return 'Ошибка';
        default: return 'Подтвердить сопоставление тарифов';
      }
    }

  }
};

export default mapper({
  connection: null,

  hotel_id: 'setHotelID',
  channel_id: 'setChannelID',
  booking_id: 'setBookingID',
  wubook_rooms: null,
  wubook_rates: null,
  wubook_rate_plans: null,
  procedure_started: {default: false},
  activation_confirmed: {default: false},
  channel_inited: {default: false},
  booking_rooms: null,
  booking_rates: null,
  rate_upload_status: null,
  room_upload_status: null,
  mapping: null,
  rate_mapping: null,
  active: {default: false},

  is_premium: null
}, store)