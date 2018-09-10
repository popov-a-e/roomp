
export default {
  namespaced: true,
  state: () => ({
    rooms: [],
    selected: null
  }),
  mutations: {
    init: (state, rooms) => {
      state.rooms = rooms;
    },
    selectRoom: (state, room) => state.selected = room,

    hideRoom: state => state.selected = null,

    updateRoom: (state, room) => {
      const roomIndex = state.rooms.findIndex(r => r.id === room.id);

      if (roomIndex >= 0) {
        state.rooms.splice(roomIndex, 1, room);
      } else {
        state.rooms.push(room);
      }
    },

    deleteRoom: (state, roomID) => {
      const roomIndex = state.rooms.findIndex(r => r.id === roomID);

      if (roomIndex >= 0) state.rooms.splice(roomIndex, 1);
    }
  },
};