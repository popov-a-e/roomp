"use strict";

import mapper from '../../helpers/mapper';
import tableFactory from '../tableFactory';

const store = tableFactory({
  name: 'promo',
  rowURL: '/promo/$id',
  resourceURL: '/promo_codes',
  filters: ['code', 'city']
});


const from = new Date();
const till = new Date();
from.setMonth(from.getMonth() - 1);

export default mapper({
  onlyActive: {default: true},
}, store);