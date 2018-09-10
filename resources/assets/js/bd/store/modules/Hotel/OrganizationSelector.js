export default {
  namespaced: true,

  state: () => ({
    organizations: [],
    name: ''
  }),

  mutations: {
    setOrganizations: (state, value) => state.organizations = value,
    setName: (state, name) => state.name = name,
  },

  actions: {
    load({state, commit}) {
      if (!state.name) return;

      axios.get('/organizations/', {
        params: {name: state.name}
      }).then(response => {
        commit('setOrganizations', response.data);
      });
    }
  }
};
