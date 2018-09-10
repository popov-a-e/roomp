export default {
  namespaced: true,
  state: () => ({
    reservations: []
  }),
  mutations: {
    setReservations: (state, data) => state.reservations = data,

    markShow: (state, {reservationID, show}) => {
      state.reservations = state.reservations.map(reservation => {
        if (reservation.id === reservationID && !reservation.disabled) reservation.noshow = !show;

        return reservation;
      });
    },

    disableEdit: (state, ids) => state.reservations = state.reservations.map(reservation => {
      if (ids.indexOf(reservation.id) >= 0) reservation.disabled = true;

      return reservation;
    })
  },
  actions: {
    load({commit}) {
      axios.get('/unconfirmed').then(response => commit('setReservations', response.data));
    },
    confirm({commit, state, getters}) {
      axios.post('/unconfirmed', {reservations: getters.reservationsMarked.pluck('id', 'noshow')}).then(response => {
        commit('disableEdit', getters.reservationsMarked.pluck('id'));
      })
    }
  },
  getters: {
    reservationsMarked: state => state.reservations.filter(reservation => reservation.noshow !== null && !reservation.disabled)
  }
}