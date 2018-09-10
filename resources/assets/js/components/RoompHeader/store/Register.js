export default {
  namespaced: true,
  state: {
    name: null,
    email: null,
    phone_string: null,
    phone: '',
    password: null,
    password_confirmation: null,
    acceptance: false,
    code: null,
    errors: {}
  },
  mutations: {
    setName: (state, name) => state.name = name,
    setEmail: (state, email) => state.email = email,
    setPhone: (state, phone) => {
      state.phone_string = phone;
      state.phone = phone.replace(/[^\d]/ig,'');
    },
    setPassword: (state, password) => state.password = password,
    setPasswordConfirmation: (state, password) => state.password_confirmation = password,
    setCode: (state, code) => state.code = code,
    setAcceptance: (state, accept) => state.acceptance = accept,

    clearErrors: state => state.errors = {},
    setError: (state, {key, value}) => {
      const _value = value.replace(/\[\[.+]]/ig, '');

      if (_value !== value) {
        Vue.set(state.errors, key + '_link', true);
      }

      Vue.set(state.errors, key, _value);
    }
  }
}