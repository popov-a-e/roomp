"use strict";


import mapper from '../../helpers/mapper';
import tableFactory from '../tableFactory';

const store = tableFactory({
  name: 'organizations',
  rowURL: '/organizations/$id',
  resourceURL: '/organizations',
  filters: ['id', 'hotels']
});


export default mapper({}, store);