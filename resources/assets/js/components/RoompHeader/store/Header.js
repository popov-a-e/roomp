"use strict";
import Appearance from './Appearance';
import Register from './Register';

export default {
  namespaced: true,

  state: {
    user: null,
    context: 'header',
    reset_code: null,
    reset_phone: null,
    locale: 'ru',
    languages: [{id: 'ru', name: 'Русский'}, {id: 'en', name: 'English'}],
    mobile: false,
    phone: '',
    contentHeight: window.innerHeight - 60,

    currency: 'RUB',
    currencies: [],
    locales: [],
  },

  mutations: {
    setUser: (state, user) => {
      state.user = user
    },

    setResetPhone: (state, value) => state.reset_phone = value,
    setResetCode: (state, value) => state.reset_code = value,
    setLocale: (state, value) => state.locale = value,
    setCurrency: (state, value) => state.currency = value,
    setMobile: (state, value) => state.mobile = value,
    setContentHeight: state => state.contentHeight = window.innerHeight - 60,

    setPhone: (state, value) => state.phone = value.replace(/[^\d]/ig, ''),

    setCurrencies: (state, currencies) => state.currencies = currencies,
    setLocales: (state, locales) => state.locales = locales,
  },

  actions: {
    login ({state}, data) {
      axios.get('/auth/login', {
        params: data
      }).then(result => {
        switch (result.status) {
          case 200:
            window.location.reload();
            break;
          default:
            alert(result.data);
            break;
        }
      }).catch(function (error) {
        alert(error.response.data.errors[Object.keys(error.response.data.errors)[0]]);
      })
    },

    register ({state, commit, rootState}) {
      ga('send', 'event', 'login_popup', 'register');
      const promise = rootState.registrationPromise;
      const code_promise = rootState.code_promise;
      const data = state.Register;

      if (!data.acceptance) {
        alert(__("header.acceptance_required"));
        return;
      }

      commit('Register/clearErrors');

      axios.get('/auth/register', {
        params: {
          ...data,
          gid: ga.getAll()[0].get('clientId')
        }
      }).then(result => {
        switch (result.status) {
          case 201:
            if (code_promise) code_promise.resolve();
            commit('Appearance/toVerify');
            break;
          case 200:
            if (promise) promise.resolve();
            else window.location.reload();
            break;
        }
      }).catch(function (error) {
        if (promise) promise.reject(error.response);
        if (error.response.status === 423) {
          alert(__("header.wrong_code"));
          return;
        }
        if (error.response.status === 421) {
          alert(__("header.too_many_attempts"));
          return;
        }

        Object.keys(error.response.data.errors).forEach(key => {
          commit('Register/setError', {key, value: error.response.data.errors[key][0]});
        });
      })
    },

    repeatSMS ({state}) {
      let promise = axios.get('/auth/repeat_sms', {
        params: {
          phone: state.Register.phone
        }
      });

      promise.then(response => {
        console.log(response);
      });
    },

    resetPasswordByEmail ({}, email) {
      axios.get('/auth/password/reset/email', {
        params: {
          email
        }
      }).then(response => {
        alert(response.data);
      }).catch(response => {
        alert("Error");
      });
    },

    resetPasswordByPhone({state, commit}) {
      axios.get('/auth/password/reset/phone', {
        params: {
          phone: state.reset_phone,
          code: state.reset_code
        }
      }).then(response => {
        if (response.status === 201) {
          commit('Appearance/codeSent');
        }

        if (response.status === 200) {
          window.location.href = '/profile#/';
        }
      }).catch(error => {
        alert(error.response.data.message);
      });
    },

    repeatResetSMS ({state}) {
      axios.get('/auth/password/reset/repeat_sms', {
        params: {
          phone: state.reset_phone
        }
      }).then(response => {
        console.log(response);
      });
    },

    changeLocale({state}, locale) {
      ga('send', 'event', 'header', 'language', locale, null, true);
      axios.get('/lang/'+locale).then(e => window.location.reload());
    },

    changeCurrency({rootState, commit}, currency) {
      axios.get('/currency/'+currency).then(e => {
        if (rootState.Filters && rootState.Filters.maxPrice) {
          commit('Filters/setPrice', {min: 0, max: 1000000}, {root: true});

        }
        setTimeout(() => window.location.reload(), 1);
      });
    }
  },
  modules: {
    Appearance, Register
  },

  getters: {
    language: state => {
      const lang = state.languages.where('id', state.locale);

      if (!lang) return false;
      return lang.name;
    }
  }
}