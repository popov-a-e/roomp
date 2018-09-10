export default {
  namespaced: true,
  state: () => ({
    id: null,
    organization_id: null,

    name: null,
    position: null,
    phone: null,
    email: null
  }),
  mutations: {
    setName: (state, name) => state.name = name,
    setPosition: (state, position) => state.position = position,
    setPhone: (state, phone) => state.phone = phone,
    setEmail: (state, email) => state.email = email,

    setLoadingStatus: (state, value) => state.loading_status = value,

    init: (state, contactFace) => {
      for (const key in state) {
        if (!state.hasOwnProperty(key)) continue;

        if (contactFace[key] !== null) state[key] = contactFace[key];
      }
    },

    initEmpty: (state, organizationID) => {
      for (const key in state) {
        if (!state.hasOwnProperty(key)) continue;

        state[key] = null;
      }

      state.organization_id = organizationID;
    }
  },
  actions: {
    save({commit, state}) {
      const startLoading = () => commit('Hotel/setLoading', true, {root: true});
      const endLoading = () => commit('Hotel/setLoading', false, {root: true});


      startLoading();
      axios.post('/contact_faces/', except(state, ['loading_status']))
        .then(response => {
          endLoading();
        })
        .catch(error => {
          const errors = error.response.data.errors
          alert(Object.keys(errors).map(key => `${key}: ${errors[key]}`).join("\r\n"));
          endLoading();
        });
    },
  },
  getters: {
    state: state => Object.assign({}, state)
  }
};