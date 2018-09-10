"use strict";

export default new Vuex.Store({
  state: {
    token: null,
    email: '',
    password: '',
    password_confirmation: '',
    acceptance: false,
    hotel_id: null,

    errors: {},
    loading: false
  },

  mutations: {
    setEmail: (state, email) => state.email = email,
    setPassword: (state, password) => state.password = password,
    setPasswordConfirmation: (state, password) => state.password_confirmation = password,
    setToken: (state, token) => state.token = token,
    setAcceptance: (state, value) => state.acceptance = value,
    setHotelID: (state, value) => state.hotel_id = value,

    setErrors: (state, errors) => state.errors = errors,

    setLoading: (state, value) => state.loading = value,
  },

  actions: {
    register ({state, commit}) {
      commit('setLoading', true);
      axios.post('/register', except(state, ['errors'])).then(result => {
        commit('setLoading', false);
        window.location = '/';
      }).catch(error => {
        commit('setLoading', false);
        let errors = {};
        const errorsAll = error.response.data;

        for(const key in errorsAll) {
          if (!errorsAll.hasOwnProperty(key)) continue;

          if (['password', 'email'].indexOf(key) === -1) return alert(errorsAll[key][0]);

          errors[key] = errorsAll[key][0];
        }

        commit('setErrors', errors);
      });
    },
  }
});