
export default {
  namespaced: true,
  state: () => ({
    email: null,
    hotels: null,
  }),

  mutations: {
    setHotelier: (state, hotelier) => {
      state.email = hotelier.email;
      state.hotels = hotelier.hotels;
    }
  },

  getters: {
    hotelsString: state => state.hotels ? state.hotels.map(hotel => hotel.regular_name).join(', ') : null,
  }
}