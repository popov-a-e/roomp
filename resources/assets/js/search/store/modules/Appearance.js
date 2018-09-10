"use strict";

export default {
  namespaced: true,
  state: {
    additionalFiltersVisible: false,
    enlightedHotel: 0,
    hotelsScrollTop: 0,
    hotelsScrollTopTarget: 0,

    hotelsScrollHeight: 0,
    hotelsOffsetHeight: 0,
    sidebarOffsetHeight: 0,
    secondaryFiltersHeight: 0,

    animating: false,

    pageYStart: false
  },
  mutations: {
    toggleAdditionalFilters: (state) => {
      state.additionalFiltersVisible = !state.additionalFiltersVisible;
    },

    sidebarResize: (state, size) => state.sidebarOffsetHeight = size,
    secondaryFiltersResize: (state, size) => state.secondaryFiltersHeight = size,

    hotelsResize(state, sizes) {
      state.hotelsScrollHeight = sizes.scrollHeight;
      state.hotelsOffsetHeight = sizes.offsetHeight;
      state.hotelsScrollTop = sizes.scrollTop;
    },

    scroll: (state, value) => state.hotelsScrollTopTarget = value,
    wheel (state, event) {
      let dy = event.deltaY;
      if (!event.wheelDelta) {
        dy = dy > 0 ? 100 : -100;
      }

      let scrollTopNew = Math.max(0, state.hotelsScrollTopTarget + dy);

      state.hotelsScrollTopTarget = Math.min(scrollTopNew, state.hotelsScrollHeight - state.hotelsOffsetHeight);
    },

    scrollChange: (state, value) => state.hotelsScrollTop = value,

    ligntHotel: (state,value) => state.enlightedHotel = value,
    unligntHotel: (state,value) => {
      if (state.enlightedHotel === value) state.enlightedHotel = 0;
    },

    animateStart: state => state.animating = true,
    animateEnd: state => state.animating = false,
    setPageYStart: (state, value) => state.pageYStart = value,
  },
  getters: {
    scrollBarHeight: state => Math.max(50, Math.floor(state.hotelsOffsetHeight * Math.min(0.8, state.sidebarOffsetHeight / state.hotelsScrollHeight))),
    scrollBarTop: (state, getters) => Math.floor(getters.scrollBarFreeSpace * state.hotelsScrollTop / getters.scrollBarScrollMax),
    scrollBarFreeSpace: (state, getters) => state.sidebarOffsetHeight - getters.scrollBarHeight,
    scrollBarScrollMax: state => state.hotelsScrollHeight - state.hotelsOffsetHeight,

    scrollBarVisible: (state, getters) => getters.scrollBarScrollMax > 0 && !state.additionalFiltersVisible,
    secondaryFiltersOffet: state => Math.min(state.secondaryFiltersHeight, state.hotelsScrollTop)
  }
};