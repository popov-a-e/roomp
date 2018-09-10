"use strict";

export default {
  namespaced: true,
  state: {
    channel_id: null,
    hotel_id: null,
    roomp_hotel_id: null,
    room_id: null,
    rows: [],
    loading: false,
    filters: {},
    sortBy: []
  },
  mutations: {
    flush: state => state.rows = [],
    setChannel: (state, id) => state.channel_id = id,
    setHotel: (state, id) => state.hotel_id = id,
    setRoom: (state, id) => state.room_id = id,
    setRoompHotel: (state, id) => state.roomp_hotel_id = id,

    setRows: (state, rows) => state.rows = rows,
    startLoad: state => state.loading = true,
    endLoad: state => state.loading = false,

    setIDFilter: (state, filter) => Vue.set(state.filters,'id', filter),
    setNameFilter: (state, filter) => Vue.set(state.filters,'name', filter),
    setRoomIDFilter: (state, filter) => Vue.set(state.filters,'room_id', filter),
    setHotelIDFilter: (state, filter) => Vue.set(state.filters,'hotel_id', filter),


    setSort: (state, value) => {
      state.sortBy = value;
    },
  },
  actions: {
    load({commit, state}) {
      if (state.room_id) {
        axios.get('/channels/rooms/' + state.hotel_id, {
          channel_id: state.channel_id
        }).then(response => commit('setRows', response.data));
      } else {
        axios.get('/channels/hotels/', {
          channel_id: state.channel_id
        }).then(response => commit('setRows', response.data));
      }
    },
    set ({commit, state}, ID) {
      if (state.room_id) {
        return axios.post('/channels/rooms/' + state.room_id, {
          channel_id: state.channel_id,
          room_id: ID
        });
      } else {
        return axios.post('/channels/hotels/' + state.roomp_hotel_id, {
          channel_id: state.channel_id,
          hotel_id: ID
        });
      }
    },
    refresh ({dispatch, commit}) {
      commit('startLoad');
      axios.get('/channels/refresh').then(r => {
        commit('endLoad');
        dispatch('load');
      });
    }
  },
  getters: {
    rowsFiltered: state => {
      let array = state.rows.filter(r => {
        let result = true;

        for (let key in state.filters) {
          if (!state.filters.hasOwnProperty(key) || !state.filters[key]) continue;

          const needle = new RegExp(escapeRegExp(state.filters[key]),'i');

          result = result && r[key].match(needle);
        }

        return result;
      });


      if (state.sortBy.length !== 2) return array;

      const desc = Number(state.sortBy[1] === 'desc') * 2 - 1;
      const key = state.sortBy[0];

      return array.sort((a,b) => {
        if (a[key] === b[key]) return 0;

        return a[key] > b[key] ? desc : -desc;
      });
    }
  }

}