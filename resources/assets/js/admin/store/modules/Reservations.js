"use strict";

import mapper from '../../helpers/mapper';
import tableFactory from '../tableFactory';

const store = tableFactory({
  name: 'reservations',
  rowURL: '/reservations/$id',
  resourceURL: '/reservations',
  filters: ['status_name', 'hotel', 'code', 'city', 'guest_name', 'channel', 'online']
});

store.actions.generateExcel = ({store, getters}) => {
  axios.post('/reservations/report', {reservations: getters.reservationsFiltered.pluck('id')}).then(response => {
    window.open('/reservations/report/' + response.data);
  });
};

const from = new Date();
const till = new Date();
from.setMonth(from.getMonth() - 1);

export default mapper({
  from: {default: from},
  till: {default: till},
  type: {default: 'created_at'}
}, store);