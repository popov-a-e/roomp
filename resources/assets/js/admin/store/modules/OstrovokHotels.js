"use strict";

import mapper from '../../helpers/mapper';
import tableFactory from '../tableFactory';

const store = tableFactory({
  name: 'hotels',
  rowURL: false,
  resourceURL: '/channels/ostrovok/',
  filters: ['name', 'hotel_id']
});

store.actions.refreshOstrovok = ({dispatch, commit}) => {
  commit('setLoading', true);
  axios.get('/channels/ostrovok/refresh').then(response => {
    dispatch('load');
    commit('setLoading', false);
  });
};

export default mapper({
  loading: null
}, store);