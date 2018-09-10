"use strict";

export default {
  namespaced: true,
  state: () => ({
    id: null,
    form: null,
    name: null,
    CEO: null,
    CEO_short_name: null,
    INN: null,
    KPP: null,
    OGRN: null,
    OKPO: null,
    account: null,
    bank: null,
    BIC: null,

    corr_account: null,
    legal_address: null,
    fact_address: null,
    post_address: null,

    phone: null,
    email: null
  }),

  mutations: {
    setForm: (state, value) => state.form = value,
    setName: (state, value) => state.name = value,
    setCEO: (state, value) => state.CEO = value,
    setCEOShortName: (state, value) => state.CEO_short_name = value,
    setINN: (state, value) => state.INN = value,
    setKPP: (state, value) => state.KPP = value,
    setOGRN: (state, value) => state.OGRN = value,
    setOKPO: (state, value) => state.OKPO = value,
    setBank: (state, value) => state.bank = value,
    setBIC: (state, value) => state.BIC = value,
    setAccount: (state, value) => state.account = value,
    setCorrAccount: (state, value) => state.corr_account = value,
    setLegalAddress: (state, value) => state.legal_address = value,
    setFactAddress: (state, value) => {
      if (!state.post_address || state.post_address === state.fact_address)
        state.post_address = value;
      if (!state.legal_address || state.legal_address === state.fact_address)
        state.legal_address = value;

      state.fact_address = value;
    },
    setPostAddress: (state, value) => state.post_address = value,

    setPhone: (state, value) => state.phone = value,
    setEmail: (state, value) => state.email = value,

    setLoadingStatus: (state, value) => state.loading_status = value,

    init: (state, organization) => {
      for (const key in state) {
        if (!state.hasOwnProperty(key)) continue;

        if (organization[key] !== null) state[key] = organization[key];
      }
    }
  },
  actions: {
    save ({state, getters,  commit, dispatch, rootState}) {
      const startLoading = () => commit('Hotel/setLoading', true, {root: true});
      const endLoading = () => commit('Hotel/setLoading', false, {root: true});

      commit('setCEOShortName', state.CEO_short_name || getters.CEO_short_name_auto);

      startLoading();
      axios.post(`/hotel/${rootState.Hotel.hotel.id}/organization`, state)
        .then(response => {
          endLoading();
          commit('Hotel/updateOrganization', state, {root: true});
        })
        .catch(error => {
          endLoading();
          alert(error.response.data);
        });
    },
    loadCompanyData({state, commit}) {
      axios.get('/get_company_by_inn', {params: {INN: state.INN}}).then(response => {
        const data = response.data;
        if (data.inn) {
          commit('setKPP', data.kpp);
          if (data.address) commit('setFactAddress', data.address.value);
          if (data.ogrn) commit('setOGRN', data.ogrn);
          if (data.okpo) commit('setOKPO', data.okpo);
          if (data.management) commit('setCEO', data.management.name);
          if (data.opf) commit('setForm', data.opf.short);
          if (data.name) commit('setName', data.name.full);
        }
      })
    },
    loadBankData({state, commit}) {
      axios({
        method: 'GET',
        url: 'https://bik-info.ru/api.html',
        params: {
          type: 'json',
          bik: state.BIC
        },
        transformRequest: [(data, headers) => {
          delete headers.common['X-CSRF-TOKEN'];
          delete headers.common['X-Requested-With'];
          return data
        }]
      }).then(response => {
        if (response.data.error) {
          commit('setBank', 'банк с таким БИК не найден');
          commit('setCorrAccount', 'смотри выше');
        }
        else {
          commit('setBank', htmlEntities(response.data.name));
          commit('setCorrAccount', response.data.ks );
        }
      });
    }
  },
  getters: {
    CEO_short_name_auto: state => {
      if (!state.CEO) return '';

      let name_pieces = state.CEO.trim().split(' ');

      if (name_pieces.length < 2) return '';

      name_pieces[name_pieces.length - 1] = name_pieces[name_pieces.length - 1][0] + '.';

      if (name_pieces.length >= 3) name_pieces[name_pieces.length - 2] = name_pieces[name_pieces.length - 2][0] + '.';

      return name_pieces.join(' ');
    },
    state: state => Object.assign({}, state),
  }
};