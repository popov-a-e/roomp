"use strict";

import HotelForm from './Hotel/HotelForm';
import ContactFace from './Hotel/ContactFace';
import Organization from './Hotel/Organization';
import Rooms from './Hotel/Rooms';
import NewHotel from './Hotel/NewHotel';
import OfferStatus from './Hotel/OfferStatus';
import OrganizationSelector from './Hotel/OrganizationSelector';
import HotelierSelector from './Hotel/HotelierSelector';
import Hotelier from './Hotel/Hotelier';

import router from './../../router';

let timeoutID = 0;

export default {
  namespaced: true,
  state: () => ({
    hotel: null,

    is_loading: false,

    deferTimeoutID: null,

    highlighted: false,

    editable: false,

    canChangeOrganization: true,
    organizationSelectorVisible: false,
    hotelierSelectorVisible: false,

    hotels_in_network: 0,
  }),
  mutations: {
    setHotel: (state, hotel) => state.hotel = hotel,

    setLoading: (state, loading) => state.is_loading = loading,

    setDeferTimeoutID: (state, id) => state.deferTimeoutID = id,

    switchToCreating: state => state.canChangeOrganization = true,
    switchToSelecting: state => state.canChangeOrganization = false,

    updateHotel: (state, newState) => {
      for (const key in newState) {
        if (!newState.hasOwnProperty(key)) continue;

        Vue.set(state.hotel, key, newState[key]);
      }
    },

    edit: state => state.editable = true,
    stopEdit: state => state.editable = false,

    updateOrganization: (state, newState) => {
      for (const key in newState) {
        if (!newState.hasOwnProperty(key)) continue;

        Vue.set(state.hotel.organization, key, newState[key]);
      }
    },

    highlight: state => state.highlighted = true,
    unhighlight: state => state.highlighted = false,

    flush: state => state.hotel = null,

    setHotelsInNetwork: (state, value) => {
      state.hotels_in_network = value;
      state.canChangeOrganization = value <= 1;
    },

    showOrganizationSelector: state => state.organizationSelectorVisible = true,
    hideOrganizationSelector: state => state.organizationSelectorVisible = false,

    showHotelierSelector: state => state.hotelierSelectorVisible = true,
    hideHotelierSelector: state => state.hotelierSelectorVisible = false,

    selectOrganization: (state, organization) => {
      Vue.set(state.hotel, 'organization', organization);
      Vue.set(state.hotel, 'organization_id', organization.id);

      for (const key in state.Organization) {
        if (!state.Organization.hasOwnProperty(key)) continue;

        if (organization[key] !== null) state.Organization[key] = organization[key];
      }

      state.canChangeOrganization = organization.hotel_number === 0;
      state.organizationSelectorVisible = false;
    },

    setHotelier: (state, hotelier) => {
      Vue.set(state.hotel, 'hotelier', hotelier);
      Vue.set(state.hotel, 'hotelier_id', hotelier.id);

      for (const key in state.Hotelier) {
        if (!state.Hotelier.hasOwnProperty(key)) continue;

        if (hotelier[key] !== null) state.Hotelier[key] = hotelier[key];
        state.hotelierSelectorVisible = false;
      }
    }
  },
  actions: {
    load ({commit, state}, hotelID) {
      return new Promise(resolve => {
        axios.get('/hotel/' + hotelID).then(response => {
          let hotel = response.data;
          hotel.rooms = hotel.rooms.map(room => {
            room.allotments = room.allotments.map(allotment => allotment.id);
            return room;
          });
          commit('setHotel', hotel);
          commit('stopEdit');
          commit('setHotelsInNetwork', hotel.hotels_in_network);

          resolve();
        });
      });
    },

    generateOffer({commit, state}) {
      axios.get('/complete/' + state.hotel.id)
        .then(response => {
          commit('Onboarding/moveHotel', {hotel: state.hotel, stage: 'signing'}, {root: true});
          router.push(`/onboarding/${state.hotel.id}`);
        }).catch(error => {
          const data = error.response.data;
          alert(Object.keys(data).map((key) => `${key}: ${data[key]}`).join("\r\n"));
        });

    },

    selectHotelier ({commit, state}, hotelier) {
      axios.post('/hotel/' + state.hotel.id + '/hotelier', {id: hotelier.id}).then(response => {
        hotelier.hotels = response.data;
        commit('setHotelier', hotelier);
      });
    },

    deferSave({commit, state}, saveFunc) {
      const delay = 600;

      return () => {
        clearTimeout(state.deferTimeoutID);
        commit('setDeferTimeoutID', setTimeout(saveFunc, delay));
      }
    },

    highlightRequired({commit}) {
      commit('highlight');

      clearTimeout(timeoutID);
      timeoutID = setTimeout(() => commit('unhighlight'), 2000);
    },

    updateStatus({commit, state}, status) {
      return new Promise(resolve => {
        axios.post(`/hotel/${state.hotel.id}/update_status/`, {status}).then(response => {
          commit('Onboarding/moveHotel', {hotel: state.hotel, stage: status}, {root: true});
          resolve();
        })
      });
    }
  },
  getters: {
    requiredFields: state => {
      const hotel = state.hotel;
      if (!hotel) return {hotel: {}, organization: {}};
      const organization = hotel.organization;

      const orgFields = ["form", "name", "fact_address", "CEO", "INN", "KPP", "account", "OGRN", "OKPO",  "BIC", "bank","corr_account", "legal_address", "post_address", "phone", "email"];
      const hotelFields = ['ru', 'regular_name', 'city_id', 'address_ru', 'reception_email'];

      let sum = 0;
      let completed = 0;

      let hotelObject = {};
      let orgObject = {};

      hotelFields.forEach(field => {
        hotelObject[field] = !!hotel[field];
      });

      orgFields.forEach(field => {
        if (organization.form === 'ИП' && ['OKPO', 'KPP', 'CEO', 'CEO_short_name'].indexOf(field) >= 0) return;

        orgObject[field] = !!organization[field];
      });

      return {hotel: hotelObject, organization: orgObject};
    },

    highlighted: (state, getters) => {
      let result = {hotel: {}, organization: {}};
      const required = getters.requiredFields;
      let first = false;

      for (const key in result) {
        if (!result.hasOwnProperty(key)) continue;

        Object.keys(required[key]).forEach(field => {
          result[key][field] = !required[key][field] && state.highlighted;
          if (result[key][field] && !first) {
            first = true;
            result[key][field] = 2;
          }
        });
      }

      return result;
    },

    completedBy: (state, getters) => {
      let completed = 0, sum = 0;

      Object.values(getters.requiredFields.hotel).forEach(fieldFilled => {
        if (fieldFilled) completed ++;
        sum++;
      });
      Object.values(getters.requiredFields.organization).forEach(fieldFilled => {
        if (fieldFilled) completed ++;
        sum++;
      });

      return Math.floor(100 * completed / sum);
    },

    stage: state => {
      if (!state.hotel) return false;

      return state.hotel.lead && state.hotel.lead.stage;
    }
  },
  modules: {HotelForm, ContactFace, Organization, Rooms, NewHotel, OfferStatus, OrganizationSelector, Hotelier, HotelierSelector}
};