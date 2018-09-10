"use strict";

export default {
  namespaced: true,
  state: {
    filters: false,
    sort: false,
    mapVisible: false,
    datepickerVisible: false,
    guest_overlay_visible: false
  },
  mutations: {
    showFilters: state => state.filters = true,
    toggleSort: state => state.sort = !state.sort,
    toggleMap: state => state.mapVisible = !state.mapVisible,
    toggleFilters: state => state.filters = !state.filters,
    hideDatepicker: state => {
      state.datepickerVisible = false;
      window.overlayCount --;
    },
    toggleGuestOverlay: state => {
      state.guest_overlay_visible = !state.guest_overlay_visible;
      window.overlayCount += state.guest_overlay_visible ? 1 : -1;
    }
  },
  actions: {},
  getters: {}
}