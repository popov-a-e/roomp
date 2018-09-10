"use strict";

import mapper from '../../helpers/mapper';
import tableFactory from '../tableFactory';

const store = tableFactory({
  name: 'hotels',
  rowURL: '/hotels/$id',
  resourceURL: '/hotels',
  filters: ['id', 'name', 'city', 'address', 'phone', 'email', 'regular_name','offer_accepted', 'channel_manager', 'fund']
});

const state = store.state();

store.state = () => ({
  ...state,
  stage: 'active'
});

store.mutations.setStageFilter = (state, stage) => state.stage = stage;

store.getters.hotels = store.getters.hotelsFiltered;
store.getters.hotelsByCategory = (state,getters) => {
  let hotels = {
    active: [],
    disabled: [],
    deleted: [],
    all: getters.hotels,
    signing: [],
    initial: [],
    signed: []
  };

  getters.hotels.forEach(hotel => {
    if (!hotels[hotel.status]) dd (hotel.status);
    hotels[hotel.status].push(hotel);
  });

  return hotels;
};

store.getters.hotelsUnfiltered = state => state.hotels;
store.getters.hotelsFiltered = (state, getters) => getters.hotelsByCategory[state.stage] || [];
store.getters.fund = (state, getters) => getters.hotelsFiltered.reduce((p,c) => p + c.fund,0);

export default mapper({
  fullnessVisibleHotelID: null
}, store);