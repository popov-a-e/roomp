export default {
  namespaced: true,
  state: {
    rooms_selected: false,
    classChecked: null,
    enlightedHotel: 0,
    overlayVisible: false,
    paymentOverlayVisible: false
  },
  mutations: {
    checkClass: (state, cl) => state.classChecked = cl,
    ligntHotel: (state, value) => state.enlightedHotel = value,
    unligntHotel: (state, value) => {
      if (state.enlightedHotel === value) state.enlightedHotel = 0;
    },
    toggleOverlay: state => {
      state.overlayVisible = !state.overlayVisible;

      window.overlayCount += state.overlayVisible ? 1 : -1;
    },
    togglePaymentOverlay: (state, toHeader = false) => {
      state.paymentOverlayVisible = !state.paymentOverlayVisible

      window.overlayCount += state.paymentOverlayVisible ? 1 : -1;
    }
  }
};