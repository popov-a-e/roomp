"use strict";

import mapper from '../../helpers/mapper';

import router from '../../router';

class Room {
  constructor(room, state) {
    this.room = room;
    this.allotment = room.allotments[0].id;
    this.children = 0;
    this.adults = 1;
    this.infants = 0;
    this.state = state;
    this.priceManual = false;
    this.manualPrice = false;
  }

  togglePriceManual() {
    this.priceManual = !this.priceManual;
  }

  get policy() {
    return this.state.policy;
  }

  get capacity() {
    return Object.keys(this.room.prices).length;
  }

  get totalCapacity() {
    return this.policy.bed_number + this.capacity;
  }

  get totalBedsOccupied() {
    return this.adults + this.children + this.infants;
  }

  get bedsLeft() {
    return this.totalCapacity - this.totalBedsOccupied;
  }

  get normalBedsLeft() {
    return Math.max(0, this.capacity - this.adults);
  }

  get totalBedsLeft() {
    return this.totalCapacity - this.totalBedsOccupied;
  }

  get extraBedsOccupied() {
    return this.totalBedsOccupied - this.normalBedsLeft;
  }

  get extraBedsLeft() {
    return this.totalBedsLeft - this.normalBedsLeft;
  }

  get priceForAdults() {
    if (this.capacity === 0) return 0;
    const prices = this.room.prices;

    if (this.capacity >= this.adults) return prices[this.adults];
    return prices[this.capacity] + (this.adults - this.capacity) * this.policy.price_adults;
  }

  get priceForChildren() {
    return this.policy.price_children * this.children;
  }

  get priceForInfants() {
    return this.policy.price_infants * this.infants;
  }

  get priceDefault( ) {
    return this.priceForAdults + this.priceForChildren + this.priceForInfants;
  }

  get price() {
    return this.manualPrice || this.priceDefault;
  }

  get adultsMax() {
    return this.policy.price_adults !== null ? this.totalBedsLeft + this.adults : this.normalBedsLeft + this.adults;
  }

  get childrenMax() {
    return this.policy.price_children !== null ? this.extraBedsLeft + this.children : 0;
  }

  get infantsMax() {
    return this.policy.price_infants !== null ? this.extraBedsLeft + this.infants : 0;
  }


  get adultsArray() {
    return Array.from({length: this.adultsMax}).map((stub, id) => id + 1);
  }

  get childrenArray() {
    return Array.from({length: this.childrenMax + 1}).map((stub, id) => id);
  }

  get infantArray() {
    return Array.from({length: this.infantsMax + 1}).map((stub, id) => id);
  }

  get adultsList() {
    return Array.from({length: this.adultsMax}).map((stub, id) => ({
      id: id + 1,
      name: pluralize('common.adults', id + 1)
    }));
  }

  get childrenList() {
    return Array.from({length: this.childrenMax + 1}).map((stub, id) => ({
      id: Number(id),
      name: pluralize('common.children', id)
    }));
  }

  get infantList() {
    return Array.from({length: this.infantsMax + 1}).map((stub, id) => ({
      id: Number(id),
      name: pluralize('common.children', id)
    }));
  }

  get allotmentList() {
    return this.room.allotments;
  }
}

const store = {
  state() {
    return {}
  },
  mutations: {
    addRoom: (state, room) => {
      state.selected_rooms.push(new Room(room, state));
      const room_id = room.id;

      state.rooms = state.rooms.map(room => {
        if (room.id !== room_id) return room;
        room.count--;
        return room;
      });
    },

    deleteRoom: (state, room) => {
      const index = state.selected_rooms.findIndex(room => room === room);

      state.selected_rooms.splice(index, 1);
    },

    setPriceManual(state, payload) {
      const index = state.selected_rooms.findIndex(room => room === payload.room);

      let room = payload.room;

      room.manualPrice = Number(payload.value);
      state.selected_rooms.splice(index, 1, room);
    },

    setAllotment(state, payload) {
      const index = state.selected_rooms.findIndex(room => room === payload.room);
      console.log(payload);

      let room = payload.room;

      room.allotment = payload.value;
      state.selected_rooms.splice(index, 1, room);
    },

    setAdultNumber(state, payload) {
      const index = state.selected_rooms.findIndex(room => room === payload.room);

      let room = payload.room;

      room.adults = payload.value;
      state.selected_rooms.splice(index, 1, room);
    },

    setChildrenNumber(state, payload) {
      const index = state.selected_rooms.findIndex(room => room === payload.room);

      let room = payload.room;

      room.children = payload.value;
      state.selected_rooms.splice(index, 1, room);
    },

    setInfantNumber(state, payload) {
      const index = state.selected_rooms.findIndex(room => room === payload.room);

      let room = payload.room;

      room.infants = payload.value;
      state.selected_rooms.splice(index, 1, room);

    }
  },
  actions: {
    getHotels({commit}) {
      axios.get('/reservations/hotels').then(response => {
        commit('setHotels', response.data);
        commit('setRooms', []);
        commit('setSelectedRooms', []);
      });
    },
    getRooms({commit, state}) {
      if (!state.from || !state.till) return;
      axios.get('/reservations/rooms/', {
        params: {
          hotel_id: state.hotel_id,
          from: state.from.toISODateString(),
          till: state.till.toISODateString()
        }
      }).then(response => {
        commit('setRooms', response.data);
        commit('setSelectedRooms', []);
      });
    },
    getPolicy({commit, state}) {
      axios.get(`/hotel/${state.hotel_id}/policy`).then(response => commit('setPolicy', response.data));
    },
    book({state}, e) {
      e.preventDefault();
      const query = {
        from: state.from.toISODateString(),
        till: state.till.toISODateString(),
        guest_name: state.name,
        hotel_id: state.hotel_id,
        phone: state.phone,
        email: state.email,
        comment: state.comment,
        online: state.online
      };

      query.occupancies = state.selected_rooms.map(room => ({
        adults: room.adults,
        children: room.children,
        infants: room.infants,
        allotment: room.allotment,
        room_id: room.room.id,
        price: room.price
      }));

      axios.post('/reservations/create', query).then(response => {
        router.push('/reservations/'+response.data.code);
      });
    }
  },
  getters: {
    fromFormatted: state => state.from ? state.from.toVerboseDateString() : "Заезд",
    tillFormatted: state => state.till ? state.till.toVerboseDateString() : "Выезд",
    fromFormattedShort: state => state.from ? state.from.toVerboseDateString({short: true}) : "Заезд",
    tillFormattedShort: state => state.till ? state.till.toVerboseDateString({short: true}) : "Выезд",
    fromDayOfWeek: state => state.from ? state.from.dayOfWeek() : '',
    tillDayOfWeek: state => state.till ? state.till.dayOfWeek() : '',
    nights: state => {

      if (!state.till || !state.from) return '';

      state.from.setHours(0, 0, 0, 0);
      state.till.setHours(0, 0, 0, 0);

      return (state.till.getTime() - state.from.getTime()) / 86400000;
    },
    nightsFormatted: (state, getters) => pluralize('common.nights', getters.nights),
    sum: (state, getters, rootState) => {
      const nights = getters.nights;

      if (!state.rooms || !state.policy) return 0;

      const policy = state.policy;

      return nights * state.selected_rooms.reduce((sum, room) => sum + room.price, 0);
    },
  }
};

export default mapper({
  hotels: null,
  rooms: null,
  from: {default: new Date()},
  till: null,
  name: null,
  email: null,
  phone: null,
  hotel_id: 'setHotelID',
  policy: null,
  selected_rooms: {default: []},
  occupancies: {
    default: null
  },
  online: {default: false},
  comment: null
}, store);