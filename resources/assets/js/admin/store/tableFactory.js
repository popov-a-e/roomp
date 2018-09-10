"use strict";
import router from '../router';
import {escapeRegExp} from '../../helpers';

export default function (params) {
  const resourceURL = params.resourceURL;
  const rowURL = params.rowURL;
  const name = params.name;

  const mutationName = 'set' + name.toCamelCase();
  const getterName = name + 'Filtered';

  const getter = state => {
    let array = state[name].filter(r => {
      let result = true;

      for (let key in state.filters) {
        if (!state.filters.hasOwnProperty(key) || !state.filters[key]) continue;

        if (state.filters[key] instanceof Array) {
          let preresult = false;

          state.filters[key].forEach(str => {
            const needle = new RegExp(escapeRegExp(str), 'i');

            preresult = preresult || (r[key] && !!r[key].match(needle));
          });

          if (state.filters[key].length === 0) preresult = true;

          result &= preresult;
        } else {
          const filter = state.filters[key];
          const value = r[key];
          let match = false;

          switch (typeof filter) {
            case 'string':
              const needle = new RegExp(escapeRegExp(filter), 'i');
              match = value && value.match(needle); break;

            default: match = filter === value; break;
          }

          result = result && match;
        }
      }

      return result;
    });


    if (state.sortBy.length !== 2) return array;

    const desc = Number(state.sortBy[1] === 'desc') * 2 - 1;
    const key = state.sortBy[0];

    return array.sort((a, b) => {
      if (a[key] === b[key]) return 0;

      return a[key] > b[key] ? desc : -desc;
    });
  };

  let store = {
    namespaced: true,
    state () {
      let basic = {
        filters: {},
        sortBy: [],
        isLoading: false
      };

      basic[name] = [];

      return basic;
    },
    mutations: {
      setSort: (state, value) => state.sortBy = value,
      setIsLoading: (state, value) => state.isLoading = value,
    },
    actions: {
      load({state, commit}, params) {
        return new Promise((resolve, reject) => {
          if (state.isLoading) return;
          commit('setIsLoading', true);
          axios.get(resourceURL, {params}).then(response => {
            commit(mutationName, response.data);
            commit('setIsLoading', false);
            resolve();
          });
        });
      }
    },
    getters: {}
  };

  if (rowURL) store.mutations.rowClick = (state, id) => router.push({path: rowURL.replace('$id', id)});

  params.filters && params.filters.forEach(filterName => {
    const mutationName = 'set' + filterName.toCamelCase() + 'Filter';
    store.mutations[mutationName] = (state, filter) => Vue.set(state.filters, filterName, filter);
  });

  store.mutations[mutationName] = (state, rows) => state[name] = rows;
  store.getters[getterName] = getter;

  return store;
};