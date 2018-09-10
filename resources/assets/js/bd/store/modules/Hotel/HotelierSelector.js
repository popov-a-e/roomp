export default {
  namespaced: true,

  state: () => ({
    hotels: [],
    name: ''
  }),

  mutations: {
    setHotels: (state, value) => state.hotels = value,
    setName: (state, name) => state.name = name,
  },

  actions: {
    load({state, commit}) {
      if (!state.name) return;

      axios.get('/hotels_by_name/', {
        params: {name: state.name}
      }).then(response => {
        commit('setHotels', response.data);
      });
    }
  }
};
