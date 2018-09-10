export default {
  namespaced:true,
  state: () => ({
    documents: [],
    discount: false
  }),
  mutations: {
    setDocuments: (state, data) => state.documents = data,
    setDiscount: (state, data) => state.discount = data
  },
  actions: {
    getDocuments ({commit}) {
      axios.get('/finance/documents').then(response => {
        commit('setDocuments', response.data);
      });
    },
    getDiscount({commit}) {
      axios.get('/finance/discount').then(({data}) => commit('setDiscount', data));
    }
  }
}