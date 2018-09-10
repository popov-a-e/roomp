"use strict";

import Header from './../../components/RoompHeader/store/Header';
import Footer from './../../components/RoompFooter/store/Footer';
import {months} from './../../helpers';

import overlayPlugin from '../../plugins/overlayPlugin';

export default new Vuex.Store({
  state: {
    name: 1,
    password: '',
    password_confirmation: '',
    phone: '',
    email: '',
    sex: '',
    birthday: null,
    reservations: []
  },
  mutations: {
    initialize: (state, user) => {
      state.name = user.name;
      state.phone = user.phone;
      state.email = user.email || '';
      if (user.birthday) {
        state.birthday = new Date(user.birthday);
      } else {
        let d = new Date();
        d.setUTCHours(0, 0, 0, 0);
        d.setDate(1);
        d.setMonth(0);
        d.setFullYear(new Date().getFullYear() - 18);

        state.birthday = d;
      }
      state.sex = user.sex || 1;
    },

    setSex: (state, value) => state.sex = value,
    setPhone: (state, value) => state.phone = value,
    setEmail: (state, value) => state.email = value,
    setName: (state, value) => state.name = value,

    setDate: (state, value) => {
      const d = new Date(state.birthday.getTime());
      d.setDate(value);
      state.birthday = d;
    },
    setMonth: (state, value) => {
      const d = new Date(state.birthday.getTime());
      const day = state.birthday.getDate();
      d.setDate(1);
      d.setMonth(value);
      d.setDate(d.daysInMonth());
      if (d.daysInMonth() >= day) {
        d.setDate(day);
      }
      state.birthday = d;
    },
    setYear: (state, value) => {
      const d = new Date(state.birthday.getTime());
      const day = state.birthday.getDate();
      d.setDate(1);
      d.setFullYear(value);
      d.setDate(d.daysInMonth());
      if (d.daysInMonth() >= day) {
        d.setDate(day);
      }
      state.birthday = d;
    },

    setPassword: (state, value) => state.password = value,
    setPasswordConfirmation: (state, value) => state.password_confirmation = value,

    setReservations: (state, value) => state.reservations = value,
  },
  getters: {
    days (state) {
      return Array.from({length: state.birthday.daysInMonth()}, (v, k) => k + 1);
    },
    months () {
      return months;
    },
    years () {
      return Array.from({length: 70}, (v, k) => new Date().getFullYear() - 18 - k);
    }
  },
  actions: {
    change ({state}) {
      let promise = axios.post('/profile/change', {
        name: state.name,
        phone: state.phone,
        email: state.email,
        sex: state.sex,
        birthday: state.birthday
      });

      promise.then(response => {
        alert(response.data);
      });
    },
    changePassword ({state}){
      let promise = axios.post('/profile/change_password', {
        password: state.password,
        password_confirmation: state.password_confirmation,
      });

      promise.then(response => {
        alert(response.data);
      });
    },
    getReservations ({commit}) {
      let promise = axios.get('/profile/reservations');

      promise.then(response => {
        commit('setReservations',response.data);
      });
    }
  },
  modules: {Header, Footer},

  plugins: [overlayPlugin]
});