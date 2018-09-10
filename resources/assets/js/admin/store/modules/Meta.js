"use strict";

import mapper from '../../helpers/mapper';

const store = {
  actions: {
    loadAll({state, commit}) {
      return new Promise((resolve, reject) => axios.get('/meta').then(response => {
        const data = response.data;
        commit('setRoomClasses', data.room_classes);
        commit('setAllotments', data.allotments);
        commit('setRoomAmenities', data.room_amenities);
        commit('setHotelAmenities', data.hotel_amenities);
        commit('setCities', data.cities);
        commit('setLanguages', data.languages);
        commit('setChannels', data.channels);
        commit('setCurrencies', data.currencies);
        commit('setCountries', data.countries);
        resolve();
      }));
    }
  }
};

export default mapper({
  room_classes: {default: []},
  allotments: {default: []},
  room_amenities: {default: []},
  hotel_amenities: {default: []},
  cities: {default: []},
  languages: {default: []},
  channels: {default: []},
  currencies: {default: []},
  countries: {default: []},
}, store).store;
