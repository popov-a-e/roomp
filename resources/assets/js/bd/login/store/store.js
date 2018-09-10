"use strict";

export default new Vuex.Store({
  state: {
    email: null,
    password: null
  },

  mutations: {
    setEmail: (state, email) => state.email = email,
    setPassword: (state, password) => state.password = password,
  },

  actions: {
    login ({state}) {
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
        alert(Object.keys(error.response.data).map((v, k) => `${v}: ${error.response.data[v]}`).join("\r\n"));
      })
    },
  }
});
