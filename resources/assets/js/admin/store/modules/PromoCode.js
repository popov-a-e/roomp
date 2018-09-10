"use strict";

import mapper from './../../helpers/mapper';
import router from '../../router';

const store = {
  namespaced: true,
  mutations: {
    setPromoCode: (state, data) => {
      for (let key in data) {
        if (!data.hasOwnProperty(key)) continue;
        if (key === 'from' && data.from) {
          state.from = new Date(data.from);
          continue;
        }
        if (key === 'till' && data.till) {
          state.till = new Date(data.till);
          continue;
        }
        state[key] = data[key];
      }
    },
    setDates: (state, {from, till}) => {
      state.from = from && from.toISODateString();
      state.till = till && till.toISODateString();
    },
    setSource: (state, {type, value}) => Vue.set(state.sources, type, value),
    toggleRow: (state, row) => {
      const type = state.adminOverlayType;
      if (!state.filter[type]) {
        const defaultValue = {
          include: true,
          ids: [row.id]
        };

        Vue.set(state.filter, type, defaultValue);
      }
      else {
        const index = state.filter[type].ids.findIndex(el => el === row.id);
        let obj = state.filter[type];
        let arr = obj.ids;

        if (index >= 0) {
          arr.splice(index, 1);
        } else {
          arr.push(row.id);
        }

        obj.ids = arr;

        if (arr.length === 0) {
          Vue.delete(state.filter, type);
        } else {
          Vue.set(state.filter, type, obj);
        }
      }
    },
    setInclude: (state, {type, value}) => {
      if (!state.filter[type]) return;
      state.filter[type].include = value;
    }
  },
  actions: {
    load ({commit, state}) {
      axios.get('/promo_codes/'+state.ID).then(response => {
        commit('setPromoCode', response.data);
      });
    },
    loadSource({commit}, type) {
      return new Promise(resolve => {
        axios.get('/list/' + type).then(response => {
          commit('setSource', {type, value: response.data});
          resolve();
        });
      });
    },
    set({commit, state, dispatch}, force = false) {
      commit('setPromoCodeSaveStatus', 'loading');
      commit('setNeedApproval', false);
      axios.post('/promo_codes' + (force ? '/force' : ''), {
        ...only(state, ['id', 'code', 'filter', 'from', 'till', 'user_id', 'is_used', 'discount'])
      }).then(response => {
        commit('setPromoCodeSaveStatus', 'success');
        setTimeout(() => commit('setPromoCodeSaveStatus', null), 2000);
        if (!state.id) {
          dispatch('Router/routeClose', '/promo/create', {root: true});
          router.push('/promo/' + response.data);
        }
      }).catch(err => {
        commit('setPromoCodeSaveStatus', 'error');

        if(err.response.status === 409) {
          commit('setNeedApproval', true);
        }

        alert(err.response.data);
      });
    },
    deactivate({dispatch, state}) {
      axios.put(`/promo_codes/${state.id}/deactivate`).then(response => dispatch('load'));
    },
    activate({dispatch, state}) {
      axios.put(`/promo_codes/${state.id}/activate`).then(response => dispatch('load'));
    },
    del({state, dispatch}) {
      if (!confirm("Вы уверены?")) return;
      axios.delete(`/promo_codes/${state.id}`).then(() => {
        dispatch('Router/routeClose', '/promo/' + state.id, {root: true});
        router.push('/promo');
      }).catch(err => {
        alert(err.response.data);
      });
    }
  },
  getters: {
    discountTypeValue: state => {
      if (!state.discount) return [];

      return state.discount.split(' ');
    },
    promoCodeButtonString: state => {
      switch (state.promoCodeSaveStatus) {
        case "loading": return "Загрузка";
        case "success": return "Успешно!";
        case "error": return "Ошибка!";
        default: return "Применить";
      }
    }
  }
};

export default mapper({
  adminOverlaySource: {default: false},
  adminOverlayType: null,
  promoCodeSaveStatus: null,

  hotel: null,
  ID: null,
  id: null,
  code: null,
  discount: null,
  admin_id: null,
  filter: {default: {}},
  from: {default: new Date()},
  till: null,
  user_id: null,
  deactivated: null,
  sources: {default: {}},
  need_approval: null,
  is_used: {
    mutation: 'setOneTime',
    function: (state, value) => state.is_used = value ? false : null,
  }
}, store);