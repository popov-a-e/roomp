"use strict";

import mapper from '../../helpers/mapper';

const store = {
  mutations: {
    setBD: (state, value) => {
      state.id = value.id;
      state.name = value.name;
      state.phone = value.phone;
      state.email = value.email;
      state.amo_id = value.amo_id;
    },
    cancel: state => {
      Object.keys(state.bd_initial).forEach(key => state[key] = state.bd_initial[key]);
    }
  },
  actions: {
    load ({commit, state}) {
      axios.get('/business_developers/' + state.ID).then(response => {
        commit('setUploadStatus', null);
        commit('setBD', response.data);
        commit('setBDInitial', response.data);
      });
    },
    apply({commit, getters, dispatch, state}) {
      if (state.upload_status === 'success') return;
      commit('setUploadStatus', 'loading');
      axios.post('/business_developers/', {...getters.BD, password: state.password}).then(response => {
        commit('setUploadStatus', 'success');
        setTimeout(() => {
          dispatch('load');
        }, 1500);
      }).catch(response => commit('setUploadStatus', 'error'));
    }
  },
  getters: {
    uploadStatus: state => {
      switch (state.upload_status) {
        case 'loading':
          return 'Загрузка...';
        case 'success':
          return 'Успешно!';
        case 'error':
          return 'Ошибка';
        default:
          return 'Подтвтердить изменения';
      }
    },
    BD: state => only(state, ['id', 'name', 'phone', 'email', 'amo_id']),
    changes: (state, getters) => !objectsEqual(getters.BD, state.bd_initial)
  }
};

export default mapper({
  id: null,
  ID: null,
  name: null,
  phone: null,
  email: null,
  amo_id: null,
  password: null,
  upload_status: null,
  bd_initial: 'setBDInitial'
}, store);