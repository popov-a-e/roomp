"use strict";

import Header from '../../components/ExtranetHeader/store/Header.js';

export default new Vuex.Store({
  state: {
    email: null,
    password: null,

    errors: {},
  },

  mutations: {
    setEmail: (state, email) => state.email = email,
    setPassword: (state, password) => state.password = password,

    setErrors: (state, errors) => state.errors = errors,
  },

  actions: {
    login ({state,commit}) {
      axios.post('/login', state).then(result => {
        switch (result.status) {
          case 200:
            window.location.reload();
            break;
          default:
            alert(result.data);
            break;
        }
      }).catch(function (error) {
        let errors = {};
        const errorsAll = error.response.data.errors;

        for(const key in errorsAll) {
          if (!errorsAll.hasOwnProperty(key)) continue;

          errors[key] = errorsAll[key][0];
        }

        commit('setErrors', errors);
      })
    },
  },
  modules: {
    Header
  },
});