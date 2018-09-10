"use strict";

import mapper from '../../../helpers/mapper';

const store = {
  namespaced: true,
  state: {
    editable: false,
    old: null,
    hoteliersVisible: false,
    channelsVisible: false,

    contactEdited: null,
  },
  mutations: {
    edit: state => {
      state.old = Object.assign({}, state.hotel);
      state.editable = true;
    },

    setContactName: (state, value) => Vue.set(state.contactEdited, 'name', value),
    setContactPosition: (state, value) => Vue.set(state.contactEdited, 'position', value),
    setContactPhone: (state, value) => Vue.set(state.contactEdited, 'phone', value),
    setContactEmail: (state, value) => Vue.set(state.contactEdited, 'email', value),
    setContactComment: (state, value) => Vue.set(state.contactEdited, 'comment', value),

    setContactID: (state, id) => Vue.set(state.contactEdited, 'id', id),
    createContact: state => state.contactEdited = {name: '', position: '', phone: '', email: '', comment: ''},
    editContact: (state, contact) => state.contactEdited = Object.assign({}, contact),
    clearContact: state => state.contactEdited = null,
    updateContact: state => {
      let contacts = state.hotel.organization.contacts || [];
      const newContact = Object.assign({}, state.contactEdited);
      if (!contacts.where('id', newContact.id)) contacts.push(newContact);
      else {
        contacts = contacts.map(contact => {
          if (contact.id === newContact.id) contact = newContact;

          return contact;
        });
      }

      Vue.set(state.hotel.organization, 'contacts', contacts);
    },
    removeContact: (state, contact) => state.hotel.organization.contacts.splice(state.hotel.organization.contacts.indexOf(contact),1),

    setHotelierName: (state, value) => Vue.set(state.hotel.hotelier, 'name', value),
    setHotelierPhone: (state, value) => Vue.set(state.hotel.hotelier, 'phone', value),

    cancel: state => {
      state.editable = false;
      state.hotel = state.old;
    },

    refresh: state => {
      state.editable = false;
    },

    setLatLng: (state, latlng) => {
      Vue.set(state.hotel, 'lat', latlng.lat());
      Vue.set(state.hotel, 'lng', latlng.lng());
    },

    setLat: (state, lat) => Vue.set(state.hotel, 'lat', lat),
    setLng: (state, lng) => Vue.set(state.hotel, 'lng', lng),

    setRegularName: (state, e) => Vue.set(state.hotel, 'regular_name', e.target.value),
    setReceptionPhone: (state, e) => Vue.set(state.hotel, 'reception_phone', e.target.value),
    setReceptionEmail: (state, e) => Vue.set(state.hotel, 'reception_email', e.target.value),

    setAmenity: (state, amenity) => {
      let amenities = state.hotel.amenities;

      const index = amenities.indexOf(amenity.key);

      if (amenity.value) {
        if (index >= 0) return;

        amenities.push(amenity.key);
      } else {
        if (index === -1) return;

        amenities.splice(index, 1);
      }

      Vue.set(state.hotel, 'amenities', amenities);
    },

    setCheckin: (state, e) => Vue.set(state.hotel, 'checkin', e.target.value),
    setCheckout: (state, e) => Vue.set(state.hotel, 'checkout', e.target.value),

    setPaymentOnline: (state, p) => Vue.set(state.hotel, 'payment_online', p.value),
    setPaymentByCash: (state, p) => Vue.set(state.hotel, 'payment_by_cash', p.value),
    setPaymentByCard: (state, p) => Vue.set(state.hotel, 'payment_by_card', p.value),

    setAddress: (state, e) => Vue.set(state.hotel, 'address_' + state.language, e.target.value),
    setCity: (state, value) => Vue.set(state.hotel, 'city_id', value.key),
    setPhotos: (state, value) => Vue.set(state.hotel, 'photos', value),
    setPhotosHub: (state, value) => Vue.set(state.hotel, 'photos_hub', value),

    setReach: (state, e) => Vue.set(state.hotel, 'reach_' + state.language, e.target.value),
    setDescription: (state, e) => Vue.set(state.hotel, 'description_' + state.language, e.target.value),
    setAdditional: (state, e) => Vue.set(state.hotel, 'additional_' + state.language, e.target.value),
    setLandmark: (state, e) => Vue.set(state.hotel, 'landmark_' + state.language, e.target.value),

    setBreakfast: (state, data) => Vue.set(state.hotel, 'breakfast', data.value),
    setHotelier: (state, hotelier) => {
      state.hoteliersVisible = false;
      Vue.set(state.hotel, 'hotelier_id', hotelier.id);
      Vue.set(state.hotel, 'hotelier', hotelier);
    },

    setPrettyURL: (state, value) => Vue.set(state.hotel, 'pretty_url', value),

    setMap: (state, value) => Vue.set(state.hotel, 'map', value),

    setName: (state, value) => Vue.set(state.hotel, state.language, value),

    activate: state => state.editable && Vue.set(state.hotel, 'disabled', false),
    deactivate: state => state.editable && Vue.set(state.hotel, 'disabled', true),

    toggleHoteliers: state => state.hoteliersVisible = !state.hoteliersVisible,
    toggleChannels: state => state.channelsVisible = !state.channelsVisible,

    setChannel: (state, channel) => {
      state.channelsVisible = false;
      Vue.set(state.hotel, 'channel_id', channel.id);
      Vue.set(state.hotel, 'channel', channel);
    }
  },
  actions: {
    load({commit, state}) {
      return new Promise((resolve, reject) => axios.get('/hotels/' + state.id + '/info').then(response => {
        commit('setHotel', response.data);
        resolve();
      }));
    },

    saveContact({commit, state}) {
      const id = state.contactEdited.id || '';
      const url = `/hotels/${state.id}/contacts/${id}`;
      const method = id ? 'put' : 'post';

      axios[method](url, state.contactEdited).then(({data: {id}}) => {
        commit('setContactID', id);
        commit('updateContact');
        commit('clearContact');
      });
    },

    deleteContact({commit, state}, contact) {
      if (!contact.id) return commit('deleteContact', contact);
      if (!confirm("Вы уверены?")) return ;

      axios.delete(`/hotels/${state.id}/contacts/${contact.id}`).then(response => {
        commit('removeContact', contact);
      });
    },

    deleteHotel({commit, state, dispatch, rootState}) {
      if (!confirm(`Вы действительно хотите удалить отель ${state.hotel.ru}?`)) return;

      axios.delete(`/hotels/${state.id}`).then(response => {
        if (rootState['Hotels#/hotels']) dispatch('Hotels#/hotels/load', null, {root: true});
        dispatch('Router/routeClose', `#/hotels/${state.id}`, {root: true});
        window.location.href = '#/hotels';
      });
    },

    apply({commit, state}) {
      switch (true) {
        /*case !state.hotel.hotelier:
          alert('Необходимо указать отельера');
          return;*/
        case !state.hotel.address_ru:
          alert('Необходимо указать адрес');
          return;
        case !state.hotel.regular_name:
          alert('Необходимо указать настоящее название');
          return;
        case !state.hotel.pretty_url:
          alert('Необходимо указать Pretty URL');
          return;
        case !state.hotel.ru:
          alert('Необходимо указать название Roomp');
          return;
        default:
          break;
      }

      const method = state.id ? 'put' : 'post';
      const url = state.id ? `/hotels/${state.id}` : '/hotels';

      axios[method](url, state.hotel).then(() => commit('refresh'));
    },

    createNew({state, dispatch, commit, rootState}) {
      commit('setHotel', {
        "hotelier_id": null,
        "city_id": 1,
        "disabled": true,
        "pretty_url": "",
        "lat": "0.0",
        "lng": "0.0",
        "checkin": "14:00:00",
        "checkout": "12:00:00",
        "breakfast": false,
        "payment_by_cash": false,
        "payment_online": false,
        "payment_by_card": false,
        "ru": "",
        "regular_name": "",
        "address_ru": "",
        "reception_phone": "",
        "reception_email": "",
        "head_phone": "",
        "head_email": "",
        "map": "",
        "reach_ru": "",
        "description_ru": "",
        "landmark_ru": "",
        "additional_ru": "",
        "reach_en": null,
        "description_en": null,
        "landmark_en": null,
        "additional_en": null,
        "reach_ch": null,
        "description_ch": null,
        "landmark_ch": null,
        "additional_ch": null,
        "photos": "",
        "en": null,
        "ch": null,
        "address_en": null,
        "address_ch": null,
        "photos_hub": null,
        "channel_id": null,
        "amenities": [],
        "hotelier": null,
        "channel": null
      });

      commit('edit');
    }
  },
  getters: {
    photosOriginal: state => (state.hotel && state.hotel.photos) ? state.hotel.photos.split(',').map(p => {
      return {src: p}
    }) : [],
    photoHub: state => (state.hotel && state.hotel.photos_hub) ? state.hotel.photos_hub.split(',').map(p => {
      return {src: p}
    }) : [],

    contacts: state => state.hotel && state.hotel.organization && state.hotel.organization.contacts || [],
  }
};

export default mapper({
  hotel: null,
  id: 'setID',
  language: {
    default: 'ru',
    mutation: 'selectLanguage'
  }
}, store);