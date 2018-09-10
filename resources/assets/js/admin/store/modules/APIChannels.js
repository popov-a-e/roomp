"use strict";

import tableFactory from '../tableFactory';
import mapper from '../../helpers/mapper';

import { escapeRegExp } from '../../../helpers';

const store = tableFactory({
  name: 'channels',
  resourceURL: '/api_channels',
  rowURL: false,
  filters: ['name', 'login']
});

export default mapper({}, store);