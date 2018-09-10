"use strict";

import mapper from '../../helpers/mapper';
import tableFactory from '../tableFactory';

const store = tableFactory({
  name: 'business_developers',
  rowURL: '/bd/$id',
  resourceURL: '/business_developers',
  filters: ['name', 'phone', 'email', 'amo_id']
});

export default mapper({}, store);