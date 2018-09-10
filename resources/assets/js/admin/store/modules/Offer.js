
import mapper from '../../helpers/mapper';

const store = {
  state: () => ({
  }),
  actions: {
    load({state, commit}) {
      axios.get(`/hotels/${state.hotelID}/offer`).then(response => {
        commit('setOffer', response.data);
      });
    },
    create({state, commit}) {
      axios.get(`/hotels/${state.hotelID}/offer/create`).then(response => {
        commit('setOffer', response.data);
      });
    },
    terminate({state, commit}) {
      if (!confirm('Вы уверены?')) return;
      axios.get(`/hotels/${state.hotelID}/offer/terminate`).then(response => {
        commit('setOffer', response.data);
      });
    },
    generate({state, commit}) {
      axios.get(`/hotels/${state.hotelID}/offer/generate`).then(response => {
        commit('setOffer', response.data);
      }).catch(({response: {data}}) => {
        alert(Object.keys(data).map((key) => `${key}: ${data[key]}`).join("\r\n"));
      });
    },
    regenerate({state, commit}) {
      axios.get(`/hotels/${state.hotelID}/offer/regenerate`).then(response => {
        alert('OK');
      }).catch(({response: {data}}) => {
        alert(Object.keys(data).map((key) => `${key}: ${data[key]}`).join("\r\n"));
      });
    },
    register({state, commit}) {
      axios.get(`/hotels/${state.hotelID}/offer/register`).then(response => {
        alert('Письмо отправлено');
      }).catch(({response: {data}}) => {
        alert(Object.keys(data).map((key) => `${key}: ${data[key]}`).join("\r\n"));
      });
    },
    resetPassword({state, commit}) {
      axios.get(`/hotels/${state.hotelID}/offer/reset_password`).then(response => {
        alert('Пароль сброшен, письмо отправлено');
      }).catch(({response: {data}}) => {
        alert(Object.keys(data).map((key) => `${key}: ${data[key]}`).join("\r\n"));
      });
    },
  }
};

export default mapper({
  hotelID: null,
  offer: null
}, store);