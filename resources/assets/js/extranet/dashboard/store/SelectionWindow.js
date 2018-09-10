'use strict';

export default {
  namespaced: true,
  state() {
    return {
      x0: null,
      y0: null,
      x1: null,
      y1: null,

      inRightZone: false,
      inLeftZone: false
    }
  },
  mutations: {
    setX1: (state, value) => state.x1 = value,
    setY1: (state, value) => state.y1 = value,
    setX0: (state, value) => state.x0 = value,
    setY0: (state, value) => state.y0 = value,

    rightZoneEnter: (state, distance) => state.inRightZone = distance,
    rightZoneLeave: state => state.inRightZone = false,

    leftZoneEnter: (state, distance) => state.inLeftZone = distance,
    leftZoneLeave: state => state.inLeftZone = false,
  },
}