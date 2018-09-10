"use strict";
export default {
  namespaced: true,
  state: {
    id: null,
    room: null,
    allotment: null,
    adults: 1,
    children: 0,
    infants: 0,
    type: 'add'
  },
  mutations: {
    pick: (state, {id, adults}) => {
      state.id = id;
      state.adults = adults;
      state.infants = 0;
      state.children = 0;
    },
    unpick: state => state.id = null,
    setAdults: (state, value) => state.adults = Number(value),
    setChildren: (state, value) => state.children = Number(value),
    setInfants: (state, value) => state.infants = Number(value),
    setAllotment: (state, value) => state.allotment = value,
    setRoom: (state, value) => state.room = value,
    switchToAdd: state => state.type = 'add',
    switchToChange: state => state.type = 'edit',
    edit: (state, {id, room}) => {
      ga('send', 'event', 'booking_module', 'change');
      state.type = 'edit';
      state.id = id;
      state.allotment = room.allotment;
      state.adults = room.adults;
      state.infants = room.infants;
      state.children = room.children;
      state.room = room.room;
    }
  },
  getters: {
    policy: (state, getters, rootState) => rootState.BackendData.policy,

    capacity: state => state.room ? Object.keys(state.room.prices).length : 0,

    totalCapacity: (state, getters) => getters.policy.bed_number + getters.capacity,

    totalBedsOccupied: (state, getters) => state.adults + state.children + state.infants,

    bedsLeft: (state, getters) => getters.totalCapacity - getters.totalBedsOccupied,

    normalBedsLeft: (state, getters) => Math.max(0, getters.capacity - state.adults),

    totalBedsLeft: (state, getters) => getters.totalCapacity - getters.totalBedsOccupied,

    extraBedsOccupied: (state, getters) => getters.totalBedsOccupied - getters.normalBedsLeft,

    extraBedsLeft: (state, getters, rootState) => getters.totalBedsLeft - getters.normalBedsLeft,

    priceForAdults: (state, getters, rootState) => {
      if (getters.capacity === 0) return 0;
      const prices = state.room.prices;

      if (getters.capacity >= state.adults) return prices[state.adults];
      return prices[getters.capacity] + (state.adults - getters.capacity) * getters.policy.price_adults;
    },

    priceForChildren: (state, getters) => getters.policy.price_children * state.children,

    priceForInfants: (state, getters) => getters.policy.price_infants * state.infants,

    price: (state, getters) => getters.priceForAdults + getters.priceForChildren + getters.priceForInfants,

    adultsMax: (state, getters) => getters.policy.price_adults !== null ? getters.totalBedsLeft + state.adults : getters.normalBedsLeft + state.adults,
    childrenMax: (state, getters) => getters.policy.price_children !== null ? getters.extraBedsLeft + state.children : 0,
    infantsMax: (state, getters) => getters.policy.price_infants !== null ? getters.extraBedsLeft + state.infants : 0,

    adultsArray: (state, getters) => Array.from({length: getters.adultsMax}).map((stub, id) => ({id: id + 1, name: id + 1})),
    childrenArray: (state, getters) => Array.from({length: getters.childrenMax + 1}).map((stub, id) => id),
    infantArray: (state, getters) => Array.from({length: getters.infantsMax + 1}).map((stub, id) => id),

    adultsList: (state, getters) => Array.from({length: getters.adultsMax}).map((stub, id) => ({
      id: id + 1,
      name: pluralize('common.adults', id + 1)
    })),
    childrenList: (state, getters) => Array.from({length: getters.childrenMax + 1}).map((stub, id) => ({
      id: Number(id),
      name: pluralize('common.children', id)
    })),
    infantList: (state, getters) => Array.from({length: getters.infantsMax + 1}).map((stub, id) => ({
      id: Number(id),
      name: pluralize('common.children', id)
    })),
  }
}