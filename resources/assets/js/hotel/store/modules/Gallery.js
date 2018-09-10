"use strict";

const imgWidth = 150;

const toIndex = (elem, arrlen) => {
  elem %= arrlen;
  if (elem < 0) elem += arrlen;

  return elem;
};

export default {
  namespaced: true,
  state: {
    photos: [],
    selected: 0,
    rangeStart: 0,
    direction: 'prev',
    rooms: false,
    loaded: false
  },
  mutations: {
    load: state => state.loaded = true,
    selectPhoto: (state,value) => {
      state.selected = value;
    },

    setPhotos: (state, value) => {
      state.photos = value.map(src => {
        return {src: src};
      });
    },

    setRooms: (state, rooms) => state.rooms = rooms,

    prevPhoto: state => {
      let selected = state.selected - 1;

      if (selected < 0) {
        state.selected = state.photos.length - 1;
      } else {
        state.selected = selected;
      }

      let inRange = false;

      for (let i = toIndex(state.rangeStart, state.photos.length), count = 0; count < 4; i = toIndex(i + 1, state.photos.length), count++) {
        inRange = inRange || i === selected;
      }

      if (!inRange) {
        state.direction = 'prev';
        state.rangeStart--;
      }
    },

    nextPhoto: state => {
      let selected = state.selected + 1;

      if (selected >= state.photos.length) {
        state.selected = 0;
      } else {
        state.selected = selected;
      }

      let inRange = false;

      for (let i = toIndex(state.rangeStart, state.photos.length), count = 0; count < 4; i = toIndex(i + 1, state.photos.length), count++) {
        inRange = inRange || i === selected;
      }

      if (!inRange) {
        state.direction = 'next';
        state.rangeStart++;
      }
    },

    shiftNext: state => {
      state.direction = 'next';
      state.rangeStart++;

      let inRange = false;

      for (let i = toIndex(state.rangeStart, state.photos.length), count = 0; count < 4; i = toIndex(i + 1, state.photos.length), count++) {
        inRange = inRange || i === state.selected;
      }

      if (!inRange) {
        state.selected = toIndex(state.rangeStart, state.photos.length);
      }
    },

    shiftPrev: state => {
      state.direction = 'prev';
      state.rangeStart--;

      let inRange = false;

      for (let i = toIndex(state.rangeStart, state.photos.length), count = 0; count < 4; i = toIndex(i + 1, state.photos.length), count++) {
        inRange = inRange || i === state.selected;
      }

      if (!inRange) {
        state.selected = toIndex(state.rangeStart + 3, state.photos.length);
      }
    },
  },

  getters: {
    photoSelected: (state) => {
      if (state.photos.length === 0) return '';
      return state.photos[state.selected].src;
    },
    photosVisible: state => {
      if (state.photos.length === 0) return [];

      let rangeStart = state.rangeStart;
      const photosN = state.photos.length;
      let photos = [];


      for (let i = 0; i < photosN; ++i) {
        const indx = rangeStart + i;
        const j = toIndex(indx, photosN);

        photos.push({value: state.photos[j], id: indx, i: j});
      }

      return photos;
    },
    amenities: (state,getters,rootState) => {
      if (!state.rooms) return [];
      const room_amenities = rootState.BackendData.room_amenities;
      return state.rooms.reduce((p,room) => p.concat(room.amenities.map(amenity => amenity.id)), []).unique().map(amenityID => room_amenities.where('id', amenityID).name);
    },

    allotments: (state, getters, rootState) => {
      const allotments = rootState.BackendData.allotments;
      if (!state.rooms || allotments.length === 0) return [];
      return state.rooms.reduce((p,room) => p.concat(room.allotments), []).unique().map(amenityID => allotments.where('id', amenityID).name);
    },

    roomSize: (state, getters, rootState) => {
      if (!state.rooms) return [];
      return state.rooms.reduce((p, room) => room.size > p ? room.size : p, 0);
    },

    roomClass: (state, getters, rootState) => {
      if (!state.rooms) return '';
      return rootState.BackendData.classes.where('id',state.rooms[0].room_class_id).name
    }
  }
}