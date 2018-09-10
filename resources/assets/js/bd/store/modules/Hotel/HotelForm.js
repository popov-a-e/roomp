export default {
  namespaced: true,
  state: () => ({
    id: null,

    address_ru: null,
    city_id: null,
    channel_id: null,
    regular_name: null,
    ru: null,
    reception_phone: null,
    reception_email: null,
    room_number: 1,

    dynamic_roomp_rate: false,
    dynamic_roomp_rate_discount: null,
  }),
  mutations: {
    setID: (state, id) => state.id = id,

    setAddress: (state, value) => state.address_ru = value,
    setCity: (state, value) => state.city_id = value,
    setChannel: (state, value) => state.channel_id = value,
    setRegularName: (state, value) => state.regular_name = value,
    setName: (state, value) => state.ru = value,
    setReceptionEmail: (state, value) => state.reception_email = value,
    setReceptionPhone: (state, value) => state.reception_phone = value,
    setRoomNumber: (state, value) => state.room_number = value,

    setLoadingStatus: (state, value) => state.loading_status = value,

    setDiscount: (state, value) => {
      state.dynamic_roomp_rate_discount = value;
      state.dynamic_roomp_rate = !!value;
    },

    init: (state, hotel) => {
      for (const key in state) {
        if (!state.hasOwnProperty(key)) continue;

        if (hotel[key] !== null) state[key] = hotel[key];
      }
    }
  },
  actions: {
    save({commit, state}) {
      const startLoading = () => commit('Hotel/setLoading', true, {root: true});
      const endLoading = () => commit('Hotel/setLoading', false, {root: true});


      startLoading();
      axios.post('/hotel/' + state.id, state)
        .then(response => {
          endLoading();
          commit('Hotel/updateHotel', state, {root: true});
        })
        .catch(response => {
          endLoading()
        });
    },
  },
  getters: {
    state: state => Object.assign({}, state),
  }
};