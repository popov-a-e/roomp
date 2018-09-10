export default {
  namespaced: true,
  state: () => ({
    offerVisible: false,
    offer: null,
    hotelier: null
  }),
  mutations: {
    hideOffer: state => state.offerVisible = false,
    showOffer: state => state.offerVisible = true,

    setOffer: (state, offer) => state.offer = offer,

    clear: state => state.offer = null,
    init: (state, offer) => {
      state.offer = offer;
    },
    setHotelier: (state, hotelier) => state.hotelier = hotelier,
  }
}