"use strict";

export default new Vuex.Store({
  state: {
    token: null,
    email: '',
    password: '',
    password_confirmation: '',

    errors: {}
  },

  mutations: {
    setEmail: (state, email) => state.email = email,
    setPassword: (state, password) => state.password = password,
    setPasswordConfirmation: (state, password) => state.password_confirmation = password,
    setToken: (state, token) => state.token = token,

    setErrors: (state, errors) => state.errors = errors
  },

  actions: {
    register ({state, commit}) {
      axios.post('/register', except(state, ['errors'])).then(result => {
        window.location = '/';
      }).catch(error => {
        let errors = {};
        const errorsAll = error.response.data.errors;

        for(const key in errorsAll) {
          if (!errorsAll.hasOwnProperty(key)) continue;

          errors[key] = errorsAll[key][0];
        }

        console.log(errors);
        commit('setErrors', errors);
      });
    },
  }
});