"use strict";

export default {
  namespaced: true,
  state() {
    return {
      map: null,
      currentMap: null,
      id: null,
      url: null,
      translationsPath: '',

      callback: null
    }
  },
  mutations: {
    input: (state, {key, value}) => {
      state.map[key] = value;
    },

  },
  actions: {
    save({state, commit}) {
      axios.post(state.url, {map: state.map, id: state.id}).then(response => state.callback(response.data));
    }
  },
};
