
export default {
  namespaced: true,
  state: () => ({
    address_ru: null,
    city_id: null,
    channel_id: null,
    regular_name: null,
    ru: null,
  }),
  mutations: {
    setAddress: (state, value) => state.address_ru = value,
    setCity: (state, value) => state.city_id = value,
    setChannel: (state, value) => state.channel_id = value,
    setRegularName: (state, value) => state.regular_name = value,
    setName: (state, value) => state.ru = value,

    clear: state => Object.keys(state).forEach(key => state[key] = null)
  },
  actions: {
    save({state, commit}) {
      axios.post('/hotel/new', state)
        .then(response => {
          commit('Onboarding/hideModal', null, {root: true});
          commit('Onboarding/addHotel', response.data, {root: true});
        })
        .catch(function (error) {
          console.log(error);
        })
    }
  }
}