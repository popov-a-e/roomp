export default {
  namespaced: true,

  state: {
    classes: [],
    allotments: [],
    room_amenities: [],
    policy: {}
  },
  mutations: {
    initialize(state, data) {
      for (let key in data) {
        if (!data.hasOwnProperty(key)) continue;
        state[key] = data[key];
      }
    },

    setPolicy: (state, policy) => state.policy = policy
  },
  getters: {
    adultsList: state =>
      __('common.adults_list').map((str, i) => {
        return {
          id: i + 1,
          name: str
        };
      }),
    childrenList: state =>
      __('common.children_list').map((str, i) => {
        return {
          id: i,
          name: str
        };
      }),
  }
}