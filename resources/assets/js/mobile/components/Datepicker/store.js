"use strict";

export default {
  namespaced: true,
  state: {
    from: null,
    till: null,
    direction: 'till',
    resolve: null
  },
  mutations: {
    setDate: (state, date) => {
      if (state.direction === 'from') {
        state.from = date;
        state.till = null;
        state.direction = 'till';
      } else {
        if (date < state.from) {
          state.from = date;
          state.till = null;
          state.direction = 'till';
        } else {
          state.till = date;
          state.direction = 'from';
        }
      }
    }
  },
  getters: {
    months: state => {
      let months = [];
      let date = new Date();
      while(date.getFullYear() < new Date().getFullYear() + 2) {
        months.push({
          year: date.getFullYear(),
          month: date.getMonth()
        });
        date.setMonth(date.getMonth() + 1);
      }

      return months;
    },
    from: state => {
      if (!state.from) return null;
      return {
        year: state.from.getFullYear(),
        month: state.from.getMonth(),
        date: state.from.getDate()
      }
    },
    till: state => {
      if (!state.till) return null;
      return {
        year: state.till.getFullYear(),
        month: state.till.getMonth(),
        date: state.till.getDate()
      }
    }
  }
}