"use strict";
import router from 'vue-router';
import mapper from '../../../helpers/mapper';

const store = {
  namespaced: true,
  state () {
    return {
      room: {},
      roomID: null,
      hotelID: null,
      old: {},
      editable: false,
      deleteConfirm: false
    }
  },
  mutations: {
    setID: (state, id) => state.roomID = id,
    setHotelID: (state, id) => state.hotelID = id,
    setRoom: (state, data) => state.room = data,

    delete: (state, module) => state,
    rowClick: (state, id) => {
      router.push(`/hotels/${state.hotelID}/${state.id}`);
    },

    deleteCheck: state => state.deleteConfirm = true,
    deleteReset: state => state.deleteConfirm = false,

    setClass: (state, id) => {
      Vue.set(state.room, 'room_class_id', id.key);
    },

    setHotelPhotos: (state, photos) => {
      Vue.set(state.room, 'hotel_photos', photos.join(','))
    },

    setName: (state,e) => Vue.set(state.room, 'name', e.target.value),
    setSize: (state,e) => Vue.set(state.room, 'size', e.target.value),
    setNumber: (state,e) => Vue.set(state.room, 'number', e.target.value),
    setGuestNumber: (state,e) => Vue.set(state.room, 'max_guest_number', e.target.value),

    setAllotment: (state, id) => {
      let allotments = state.room.allotments;
      const index = allotments.indexOf(id.key);

      if (id.value) {
        if (index >= 0) return;

        allotments.push(id.key);
      } else {
        if (index === -1) return;

        allotments.splice(index, 1);
      }

      Vue.set(state.room, 'allotments', allotments);
    },

    setAmenity: (state, id) => {
      let amenities = state.room.amenities;

      const index = amenities.indexOf(id.key);

      if (id.value) {
        if (index >= 0) return;

        amenities.push(id.key);
      } else {
        if (index === -1) return;

        amenities.splice(index, 1);
      }

      Vue.set(state.room, 'amenities', amenities);
    },

    setPhotos: (state, value) => {
      Vue.set(state.room, 'photos', value);
    },

    edit: state => {
      state.old = Object.assign({}, state.room);
      state.editable = true;
    },

    cancel: state => {
      state.editable = false;
      state.room = state.old;
    },

    refresh: state => {
      state.editable = false;
    }


  },
  actions: {
    load ({commit, state}) {
      axios.get(`hotels/${state.hotelID}/rooms/${state.roomID}`).then(response => {
        commit('setRoom', response.data);
      });
    },

    apply ({commit, state, dispatch}) {
      if (!state.roomID) {
        axios.post(`/hotels/${state.hotelID}/rooms/`, state.room).then(response => {
          dispatch('Router/routeClose', `/hotels/${state.hotelID}/new`, {root: true});
          router.push(`/hotels/${state.hotelID}/${response.data.id}`);
        });
      } else {
        axios.put(`/hotels/${state.hotelID}/rooms/${state.roomID}`, state.room).then(response => {
          commit('refresh');
        });
      }
    },

    confirmDelete ({state, dispatch, rootState, commit}) {
      if (!state.roomID) {
        dispatch('Router/routeClose', `/hotels/${state.hotelID}/new`, {root: true});

        return;
      }

      axios.delete(`/hotels/${state.hotelID}/rooms/${state.roomID}`).then(response => {
        dispatch('Router/routeClose', `/hotels/${state.hotelID}/${state.roomID}`, {root: true});
        if (rootState[`Hotel#${state.hotelID}`]) {
          commit(`Hotel#${state.hotelID}/deleteRoom`, state.roomID, {root: true});
        }
      }).catch(error => {
        alert('Невозможно произвести удаление. С данной комнатой связаны бронирования: '+error.response.data);
      });
    },

    createNew ({state, dispatch, commit, rootState}) {
      const module = rootState['Hotel#/hotels/'+state.hotelID];

      if (!module) {
        dispatch('Router/routeClose','/hotels/'+state.hotelID +'/new', {root: true});
        return;
      }

      commit('setRoom', {
        name: '',
        size:null,
        amenities: [],
        allotments: [],
        hotelID: state.hotelID,
        hotel_photos: module.hotel.photos,
        id: null,
        max_guest_number: 2,
        number: 1,
        photos: '',
        room_class_id: rootState.Meta.room_classes[0].id
      });

      commit('edit');
    }
  },
  getters: {
    photosOriginal: state => state.room.photos ? state.room.photos.split(',').map(photo => {return {src: photo}}) : [],
    hotelPhotos: (state, getters) => state.room.hotel_photos ? state.room.hotel_photos.split(',').filter(photo => getters.photosOriginal.indexOf(photo) === -1) : []
  }
};

export default mapper({}, store);