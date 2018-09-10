"use strict";

export default {
  namespaced: true,
  state: {
    user: null
  },
  mutations: {
    initialize: (state, user) => {
      state.user = user;
    },
  }
}