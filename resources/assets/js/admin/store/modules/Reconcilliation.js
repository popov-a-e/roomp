"use strict";

import mapper from '../../helpers/mapper';
import tableFactory from '../tableFactory';

const store = tableFactory({
  name: 'reservations',
  rowURL: null,
  resourceURL: '/reconcilliation',
  filters: ['status_name', 'code', 'guest_name', 'channel_name', 'status_name', 'active']
});

store.mutations.showAll = state => Vue.delete(state.filters, 'active');
store.mutations.showActive = state => Vue.set(state.filters, 'active', true);

store.actions.load = ({state, commit}) => {
  return new Promise((resolve, reject) => {
    if (state.isLoading) return;
    commit('setIsLoading', true);
    axios.get('/reconcilliation/', {
      params: {
        month: state.month + 1,
        year: state.year,
        hotel_id: state.hotelID
      }
    }).then(response => {
      commit('setReservations', response.data.reservations);
      commit('setIsLoading', false);
      commit('setBookingCommission', response.data.booking_commission);
      commit('setOstrovokCommission', response.data.ostrovok_commission);
      resolve();
    });
  });
};

store.actions.getDocument = ({state, commit}) => {
  commit('setDocLoading', true);
  axios.get('/reconcilliation/doc', {
    params: {
      hotel_id: state.hotelID,
      month: state.month + 1,
      year: state.year,
      all: !state.onlyActive
    }
  }).then(response => {
    commit('setDoc', response.data);
    commit('setDocLoading', false);
  });
};

store.actions.generateDoc = ({state, dispatch}) => {
  axios.get('/reconcilliation/generate_doc', {
    params: {
      hotel_id: state.hotelID,
      month: state.month + 1,
      year: state.year,
      repeat: !!state.doc.file
    }
  }).then(response => {
    dispatch('getDocument');
  });
};

store.getters.reservationsActive = state => state.reservations.filter(reservation => reservation.active);

let prevMonth = new Date();
prevMonth.setMonth(prevMonth.getMonth() - 1);
export default mapper({
  onlyActive: true,
  hotelID: null,
  booking_commission: {default: 0},
  ostrovok_commission: {default: 0},
  month: {default: prevMonth.getMonth()},
  year: {default: new Date().getFullYear()},
  doc: null,
  doc_loading: {default: false},
}, store);