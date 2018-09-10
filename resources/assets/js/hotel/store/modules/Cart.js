"use strict";

export default {
  namespaced: true,
  state: {
    from: new Date(),
    till: null,
    name: null,
    comment: '',
    rooms: [],
    loading: true
  },
  mutations: {
    setFrom: (state, value) => state.from = value,
    setTill: (state, value) => state.till = value,
    setName: (state, value) => state.name = value,
    setComment: (state, value) => state.comment = value,

    initialize: (state, value) => {
      state.from = new Date(value.from);
      state.till = new Date(value.till);
    },

    startLoading: state => state.loading = true,
    endLoading: state => state.loading = false,
    loadingError: state => state.loading = 'error'
  },
  getters: {
    fromFormatted: state => state.from ? state.from.toVerboseDateString() : __('common.checkin'),
    tillFormatted: state => state.till ? state.till.toVerboseDateString() : __('common.checkout'),
    fromFormattedShort: state => state.from ? state.from.toVerboseDateString({short: true}) : __('common.checkin'),
    tillFormattedShort: state => state.till ? state.till.toVerboseDateString({short: true}) : __('common.checkout'),
    fromDayOfWeek: state => state.from ? state.from.dayOfWeek() : '',
    tillDayOfWeek: state => state.till ? state.till.dayOfWeek() : '',
    nights: state => {
      if (!state.till || !state.from) return 0;

      state.from.setHours(0, 0, 0, 0);
      state.till.setHours(0, 0, 0, 0);

      return (state.till.getTime() - state.from.getTime()) / 86400000;
    },
    nightsFormatted: (state, getters) => pluralize('common.nights', getters.nights),
    sum: (state, getters, rootState) => {
      return getters.nights * state.rooms.reduce((sum, room) => {
        const policy = rootState.BackendData.policy;

        const roomCapacity = Object.keys(room.room.prices).length;

        if (room.adults <= roomCapacity) {
          return sum + room.room.prices[room.adults] + policy.price_children * room.children + policy.price_infants * room.infants;
        }

        const price = room.room.prices[roomCapacity] + (room.adults - roomCapacity) * policy.price_adults + policy.price_children * room.children + policy.price_infants * room.infants;

        return sum + price;
      }, 0);
    },
    originalSum: (state, getters, rootState) => {
      return getters.nights * state.rooms.reduce((sum, room) => {
          const policy = rootState.BackendData.policy;

          const roomCapacity = Object.keys(room.room.prices).length;

          console.log(room.room.prices_original[room.adults], policy.price_children_original, policy.price_infants_original);

          if (room.adults <= roomCapacity) {
            return sum + room.room.prices_original[room.adults] + policy.price_children_original * room.children + policy.price_infants_original * room.infants;
          }

          const price = room.room.prices_original[roomCapacity] + (room.adults - roomCapacity) * policy.price_adults_original + policy.price_children_original * room.children + policy.price_infants_original * room.infants;

          return sum + price;
        }, 0);
    },
    infoString: (state, getters) => {
      let children = getters.childrenFormatted;

      if (children) children = ', '+ children; else children = '';

      return `${getters.roomsFormatted}, ${getters.nightsFormatted}, ${getters.adultsFormatted}${children}`;
    },
    promoDiscount: (state, getters, rootState) => {
      if (!rootState.promo_code_data) return 0;

      let [value, type] = rootState.promo_code_data.discount.split(' ');

      switch (type) {
        case '%': return Math.floor(getters.sum * Math.min(value, 100) / 100);
        default: return getters.sum - Math.max(0, getters.sum - Number(value));
      }
    },
    originalPromoDiscount: (state, getters, rootState) => {
      if (!rootState.promo_code_data) return 0;

      let [value, type] = rootState.promo_code_data.original_discount.split(' ');

      switch (type) {
        case '%': return Math.floor(getters.originalSum * Math.min(value, 100) / 100);
        default: return getters.originalSum - Math.max(0, getters.originalSum - Number(value));
      }
    },
    promoString: (state,getters, rootState) => {
      if (!rootState.promo_code_data) return '';
      const [value, type] = rootState.promo_code_data.discount.split(' ');

      switch (type) {
        case '%': return Math.min(100, value) + ' ' + type;
        default: return toCurrency(getters.sum - Math.max(0, getters.sum - Number(value)));
      }
    },
    discountedSum: (state, getters) => getters.sum - getters.promoDiscount,
    originalDiscountedSum: (state, getters) => getters.originalSum - getters.originalPromoDiscount,
    roomsFormatted: state => pluralize('common.rooms', state.rooms.length),
    adultsFormatted: state => pluralize('common.adults', state.rooms.reduce((sum, room) => sum + room.adults, 0)),
    childrenFormatted: state => {
      const children = state.rooms.reduce((sum, room) => sum + room.children, 0);

      if (children === 0) return false;
      return pluralize('common.children', children);
    }
  }
}