"use strict";
import overlayPlugin from '../../plugins/overlayPlugin';

export default new Vuex.Store({
  state: {
    code: null,
    token: null,
    payment: false
  },

  mutations: {
    initialize (state, data) {
      state.code = data.code;
      state.token = data.token;
    }
  },
  actions: {
    cancel ({state}) {
      let promise = axios.get('/r/cancel', {
        params: {
          code: state.code,
          token: state.token
        }
      });

      promise.then(response => {
        if (response.status === 200) {
          window.location.reload();
        }
      })
    },
    checkPayment({state}) {
      let promise = axios.get(`/pay/status/${state.code}`, {
        params: {
          token: state.token
        }
      });

      promise.then(response => {
        if (response.data === true) {
          state.payment = true;
          document.querySelector('.content > div.status > span.status').innerText = __('reservation.paid');
        } else {
          state.payment = __('reservation.not_paid');
        }
      })
    },
    pay ({state}) {
      window.location.href = `/pay/${state.code}?token=${state.token}`;
    }
  },
  getters: {
    paymentStatus: state => {
      if (state.payment === false) return __('common.loading');
      else return state.payment;
    }
  },

  plugins: [overlayPlugin]
});