"use strict";


import mapper from '../../helpers/mapper';
import tableFactory from '../tableFactory';

const store = tableFactory({
  name: 'hoteliers',
  rowURL: '/hoteliers/$id',
  resourceURL: '/hoteliers',
  filters: ['id', 'email', 'hotels']
});

export default mapper({}, store);