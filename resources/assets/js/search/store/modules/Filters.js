"use strict";

export default {
  namespaced: true,
  state: {
    city: 1,
    from: '',
    till: '',
    allotments: [],
    hotel_amenities: [],
    room_amenities: [],
    price: {
      min: 0,
      max: 100000
    },
    maxPrice: 300,
    map: {},
    payment_by_cash: false,
    payment_by_card: false,
    payment_online: false,
    map_active: false
  },
  mutations: {
    setCity: (state, value) => state.city = value,
    setFrom: (state, value) => state.from = value ? value.toISODateString() : null,
    setTill: (state, value) => state.till = value ? value.toISODateString() : null,

    setAllotments: (state, value) => state.city = value,
    setPrice (state, value) {
      state.price.min = value.min;
      state.price.max = value.max;
    },

    setMaxPrice (state, value) {
      state.maxPrice = value;
      state.price.max = Math.min(value + 1, state.price.max);
    },

    setMap(state, value) {
      state.map = {
        northeast: value.northEast,
        southwest: value.southWest,
        zoom: value.zoom
      };
    },

    activateMap: state => state.map_active = true,
    deactivateMap: state => state.map_active = false,

    unsetMap: (state) => state.map = {},


    toggleAllotment (state, allotment) {
      if (allotment.value === true) {
        state.allotments.push(allotment.key);
      } else {
        state.allotments.splice(state.allotments.indexOf(allotment.key), 1);
      }
    },

    toggleHotelAmenity(state, amenity) {
      if (amenity.value === true) {
        state.hotel_amenities.push(amenity.key);
      } else {
        state.hotel_amenities.splice(state.hotel_amenities.indexOf(amenity.key), 1);
      }
    },

    toggleRoomAmenity(state, amenity) {
      if (amenity.value === true) {
        state.room_amenities.push(amenity.key);
      } else {
        state.room_amenities.splice(state.room_amenities.indexOf(amenity.key), 1);
      }
    },

    togglePaymentByCash: (state, data) => {
      state.payment_by_cash = data.value;
    },
    togglePaymentByCard: (state, data) => {
      state.payment_by_card = data.value;
    },
    togglePaymentOnline: (state, data) => {
      state.payment_online = data.value;
    },

    resetFilters: state => {
      state.allotments = [];
      state.hotel_amenities = [];
      state.room_amenities = [];
      state.price.min = 0;
      state.price.max = state.maxPrice + 1;
      state.payment_by_cash = false;
      state.payment_by_card = false;
      state.payment_online = false;
    },

    resetMap: state => {
      state.map = {};
      state.map_active = false;
    },

    resetPrice: state => {
      state.price.min = 0;
      state.price.max = state.maxPrice + 1;
    },

    initialize (state, value) {
      for (let key in value) {
        if (!value.hasOwnProperty(key) || !state.hasOwnProperty(key)) continue;

        if (value[key] === 'false' || value[key] === 'true') {
          state[key] = value[key] === 'true';
          continue;
        }

        if (key === 'map') {
          state.map = {
            northeast: {
              lat: Number(value.map.northeast.lat),
              lng: Number(value.map.northeast.lng),
            },
            southwest: {
              lat: Number(value.map.southwest.lat),
              lng: Number(value.map.southwest.lng),
            },
            zoom: Number(value.map.zoom),
          };

          continue;
        }

        if ((typeof value[key] === "object") && (value !== null)) {
          state[key] = value[key];
        } else {
          state[key] = value[key];
        }
      }
    }
  },
  getters: {
    filtersEmpty: state => {
      return (
        !state.allotments.length &&
        !state.hotel_amenities.length &&
        !state.room_amenities.length &&
        state.price.min === 0 &&
        state.price.max === state.maxPrice + 1
      );
    },
    fromFormatted: state => state.from ? new Date(state.from).toVerboseDateString() : __('common.checkin'),
    tillFormatted: state => state.till ? new Date(state.till).toVerboseDateString() : __('common.checkout'),
    fromDayOfWeek: state => state.from ? new Date(state.from).dayOfWeek() : '',
    tillDayOfWeek: state => state.till ? new Date(state.till).dayOfWeek() : '',
    nights: state => {
      if (!state.till || !state.from) return '';

      const from = new Date(state.from);
      const till = new Date(state.till);

      from.setHours(0, 0, 0, 0);
      till.setHours(0, 0, 0, 0);

      return (till.getTime() - from.getTime()) / 86400000;
    },
    nightsFormatted: (state, getters) => pluralize('common.nights', getters.nights),
  }
}