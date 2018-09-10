"use strict";

import Appearance from './modules/Appearance';
import Cart from './modules/Cart';
import BackendData from './modules/BackendData';
import CurrentRoom from './modules/CurrentRoom';
import Gallery from './modules/Gallery';
import Header from './../../components/RoompHeader/store/Header';

import overlayPlugin from '../../plugins/overlayPlugin';

const reservationData = (state, online) => ({
  from: state.Cart.from.toISODateString(),
  till: state.Cart.till.toISODateString(),
  hotel_id: state.hotelID,
  guest_name: state.Cart.name,
  comment: state.Cart.comment,
  online: online,
  promo_code: state.promo_code_data && state.promo_code_data.id,
  occupancies: state.Cart.rooms.map(room => {
    return {
      room_id: room.room.id,
      adults: room.adults,
      children: room.children,
      infants: room.infants,
      allotment: room.allotment
    };
  })
});

const book = (state, online) => {
  const promise = axios.post('/book', reservationData(state, online));

  promise.then(response => {
    if (response.status === 200) {
      const reservation = response.data;
      const page = online ? 'pay' : 'r';
      const redirect = () => window.location.href = `/${page}/${reservation.code}?token=${reservation.token}`;

      if (ga) {
        ga('send', 'pageview', {
          page: `/${page}/${reservation.code}`,
          hitCallback: redirect
        });
      } else {
        redirect();
      }
    }
  });
};

let state = {
  rooms: [],
  grouped: [],
  hotelID: 0,
  promo_code: null,
  registrationPromise: false,
  hotelPhotos: [],
  paymentOnline: false,
  paymentOffline: false,
  hasBreakfast: false,

  promo_code_data: false,
  promo_code_error: null
};

let mutations = {
  setPromoCode: (state, value) => state.promo_code = value,
  cancelPromoCode: state => state.promo_code_data = null,

  setPromise: (state, promise) => state.registrationPromise = promise,

  toggle: state => {
    if (state.Cart.rooms.length === 0) return;

    state.Appearance.rooms_selected = !state.Appearance.rooms_selected
  },

  newRooms (state, data) {
    state.rooms = data.map(room => {
      room.images = room.photos.split(',').map(src => {
        let img = new Image();
        img.src = image_url(src);

        return img;
      });

      return room;
    });

    state.Cart.rooms = [];
  },

  groupRooms (state, data) {
    state.grouped = data.reduce((free, room) => {
      room.allotments.forEach(allotment => {
        if (free.filter(row => row.class === room.room_class_id && room.allotment === allotment).length === 0) {
          free.push({
            allotment,
            class: room.room_class_id,
            rooms: []
          });
        }
        free = free.map(row => {
          if (row.class === room.room_class_id && row.allotment === allotment) {
            row.rooms.push(room.id);
          }
          return row;
        });
      });

      return free;
    }, []).map((row, i) => {
      row.price = state.rooms.where('id', row.rooms[0]).price;
      row.index = i;
      return row;
    });
  },

  commitRoom(state) {
    let roomParams = state.CurrentRoom;

    if (roomParams.type === 'add') {
      state.rooms = state.rooms.map(room => {
        if (room.id === roomParams.room.id) {
          room.count--;
        }

        return room;
      });

      state.Cart.rooms.push({
        room: state.rooms.where('id', roomParams.room.id),
        class: roomParams.room.room_class_id,
        allotment: roomParams.allotment,
        adults: roomParams.adults,
        children: roomParams.children,
        infants: roomParams.infants
      });
    } else {
      Vue.set(state.Cart.rooms[roomParams.id], 'adults', roomParams.adults);
      Vue.set(state.Cart.rooms[roomParams.id], 'children', roomParams.children);
      Vue.set(state.Cart.rooms[roomParams.id], 'infants', roomParams.infants);
    }

    state.CurrentRoom.id = null;

    state.promo_code_data = null;
  },

  removeRoom(state, index) {
    let cartroom = state.Cart.rooms[index].room;

    state.rooms = state.rooms.map(room => {
      if (room.id === cartroom.id) room.count++;

      return room;
    });

    state.Cart.rooms.splice(index, 1);
    state.promo_code_data = null;
  },

  initialize: (state, hotelID) => state.hotelID = hotelID,

  initHotelPhotos: (state, hotelPhotos) => {
    state.hotelPhotos = hotelPhotos;
    state.Gallery.photos = hotelPhotos.map(src => {
      return {src: src};
    });
  },

  heroimageGalleryOpen(state) {
    window.overlayCount++;
    state.Appearance.overlayVisible = true;
    state.Gallery.rooms = false;
    state.Gallery.photos = state.hotelPhotos.map((src, i) => {
      return {
        src
      };
    });
    state.Gallery.selected = 0;
    state.Gallery.rangeStart = 0;
  },

  galleryOpen: state => {
    window.overlayCount++;
    state.Appearance.overlayVisible = true;
    state.Gallery.selected = 0;
    state.Gallery.rangeStart = 0;
  },

  setPaymentOffline: (state, bool) => state.paymentOffline = bool,
  setPaymentOnline: (state, bool) => state.paymentOnline = bool,

  setBreakfast: (state, bool) => state.hasBreakfast = bool,

  setPromoCodeData: (state, value) => {
    state.promo_code_data = value;
  },
  setPromoCodeError: (state, value) => state.promo_code_error = value
};

let actions = {
  checkPromoCode({state, commit}) {
    axios.post('/promo/check/', Object.assign(reservationData(state), {promo_code: state.promo_code})).then(e => commit('setPromoCodeData', e.data)).catch(e => commit('setPromoCodeError', e.response.data.message));
  },

  updateRooms ({commit, state, getters}) {
    commit('Cart/startLoading');
    let promise = axios.get('/hotel_rooms', {
      params: {
        from: state.Cart.from.toISODateString(),
        till: state.Cart.till ? state.Cart.till.toISODateString() : null,
        hotel_id: state.hotelID
      }
    });

    promise.then(response => {
      commit('Cart/endLoading');
      commit('newRooms', response.data);
      commit('groupRooms', response.data);
      commit('Appearance/checkClass', getters.classes[0]);
      commit('CurrentRoom/unpick');
    }).catch(e => {
      commit('Cart/loadingError');
    });
  },

  pay ({commit, dispatch, state}) {
    ga('send', 'event', 'booking_module', 'pay_online');
    if (!state.Header.user) {
      let promise = new Promise((resolve, reject) => {
        commit('setPromise', {resolve, reject});
        commit('Appearance/togglePaymentOverlay');
        dispatch('Header/register');
      });

      promise.then(() => {
        commit('setPromise', false);
        book(state, true);
      }).catch(() => {
        commit('setPromise', false);
      });
    } else {
      book(state, true);
    }
  },

  book ({commit, state, dispatch}) {
    ga('send', 'event', 'booking_module', 'pay_at_hotel');
    if (!state.Header.user) {
      let promise = new Promise((resolve, reject) => {
        commit('setPromise', {resolve, reject});
        dispatch('Header/register');
      });

      promise.then(() => {
        commit('setPromise', false);
        book(state, false);
      }).catch(() => {
        commit('setPromise', false);
      });
    } else {
      book(state, false);
    }
  },

  updateScroll() {
    const content = document.querySelector('.content');
    const bodyScrollTop = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop || 0;
    const booking = document.querySelector('.booking');
    const footer = document.querySelector('footer');

    if (bodyScrollTop + booking.offsetHeight > content.offsetHeight - 20) {
      if (!booking.classList.contains('fixed')) {
        booking.classList.add('fixed');
      }
    } else {
      if (booking.classList.contains('fixed')) {
        booking.classList.remove('fixed');
      }
    }
  }
};

let getters = {
  free: (state) => {
    return state.grouped.filter(row => {
      return row.rooms.map(roomID => state.rooms.where('id', roomID)).some(room => room.count > 0);
    });
  },
  classes: state => {
    return state.rooms.map(room => room.room_class_id).reduce((arr, cl) => {
      if (arr.indexOf(cl) === -1) arr.push(cl);
      return arr;
    }, []).map(cl => state.BackendData.classes.where('id', cl));
  },
  cartEmpty: state => state.Cart.rooms.length === 0
};

export {state, mutations, actions, getters, book};

export default new Vuex.Store({
  state,

  mutations,

  actions,

  getters,

  modules: {
    Appearance, Cart, BackendData, Header, CurrentRoom, Gallery
  },

  plugins: [overlayPlugin]
});