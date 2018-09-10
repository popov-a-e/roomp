"use strict";

import Vue from 'vue';

const traitArray = {
  editable: store => {
    let state = store.state();

    state.edited = false;
    state.prevState = null;
    store.mutations.edit = state => {
      state.prevState = Object.assign({}, except(state, ['edited', 'prevState']));
      state.edited = true;
    };
    store.mutations.apply = state => {
      state.prevState = null;
      state.edited = false;
    };
    store.mutations.cancel = state => {
      state.edited = false;

      if (state.prevState) for (const key in state.prevState) {
        if (!state.prevState.hasOwnProperty(key) || key === 'prevState') continue;

        state[key] = state.prevState[key];
      }

      state.prevState = null;
    };

    store.state = () => Object.assign({}, state);

    return store;
  },
};

const buildMapping = (data, store, traits = []) => {
  const _state = typeof store.state === 'function' ? store.state() : store.state;
  let state = Object.assign({}, _state);
  store.namespaced = true;
  store.mutations = store.mutations || {};
  store.actions = store.actions || {};
  store.getters = store.getters || {};

  for (let key in data) {
    if (!data.hasOwnProperty(key)) continue;

    const defaultState = null;
    const defaultMutation = (state, value) => state[key] = value;
    const defaultMutationName = 'set' + key.toCamelCase();

    state[key] = defaultState;
    store.mutations[defaultMutationName] = defaultMutation;

    if (typeof data[key] === 'string') {
      store.mutations[data[key]] = defaultMutation;
    } else if (typeof data[key] === 'object' && data[key] instanceof Object) {
      const params = data[key];

      const currentDefaultState = params.hasOwnProperty('default') ? params.default : defaultState;

      if (params.nest) {
        if (!state[params.nest]) state[params.nest] = {};
        state[params.nest][key] = currentDefaultState;

        const defaultMutationNested = (state, value) => Vue.set(state[params.nest], key, value);

        store.mutations[params.mutation || defaultMutationName] = params.function || defaultMutationNested;
      } else {
        state[key] = currentDefaultState;
        store.mutations[params.mutation || defaultMutationName] = params.function || defaultMutation;
      }
    }
  }

  store.state = () => Object.assign({}, state);

  if (typeof traits === 'string') traits = [traits];

  traits.forEach(trait => {
    store = traitArray[trait](store);
  });


  return store;
};

const resolveMappingState = (data, store) => moduleID => {
  let computed = {};

  for (let key in data) {
    if (!data.hasOwnProperty(key)) continue;

    let value = {};
    let mutationName = 'set' + key.toCamelCase();


    if (typeof data[key] === 'string') {
      mutationName = data[key];
    } else if (typeof data[key] === 'object' && data[key] instanceof Object && data[key].mutation) {
      mutationName = data[key].mutation;
    }

    value.get = function get() {
      return this.$store.state[(moduleID || this.moduleID)][key];
    };

    value.set = function set(value) {
      this.$store.commit((moduleID || this.moduleID) + '/' + mutationName, value);
    };

    computed[key] = value;
  }

  const state = store.state();

  for (let key in state) {
    if (!state.hasOwnProperty(key)) continue;
    if (computed[key]) continue;

    computed[key] = function () {
      return this.$store.state[(moduleID || this.moduleID)][key];
    }
  }

  return computed;
};


const resolveMappingGetters = store => moduleID => {
  const value = {};
  const state = store.state();

  for (let key in store.getters) {
    if (!store.getters.hasOwnProperty(key)) continue;
    const prop = function () {
      return this.$store.getters[(moduleID || this.moduleID) + '/' + key];
    };

    if (state.hasOwnProperty(key)) {
      const mutationName = 'set' + key.toCamelCase();

      value[key] = {
        get: prop,
        set: function(value) {
          this.$store.commit((moduleID || this.moduleID) + '/' + mutationName, value);
        }
      };
    } else {
      value[key] = prop;
    }
  }

  return value;
};

const resolveMappingMutations = store => moduleID => {
  const value = {};

  for (let key in store.mutations) {
    if (!store.mutations.hasOwnProperty(key)) continue;

    value[key] = function (val) {
      return this.$store.commit((moduleID || this.moduleID) + '/' + key, val);
    }
  }
  return value;
};

const resolveMappingActions = store => moduleID => {
  const value = {};

  for (let key in store.actions) {
    if (!store.actions.hasOwnProperty(key)) continue;

    value[key] = function (val) {
      return this.$store.dispatch((moduleID || this.moduleID) + '/' + key, val);
    }
  }

  return value;
};

export default (params, store, traits) => {
  const _store = buildMapping(params, store, traits);

  return {
    store: _store,
    mapProps: moduleID => {
      return {
        ...resolveMappingState(params, _store)(moduleID),
        ...resolveMappingGetters(_store)(moduleID)
      }
    },
    mapMethods: moduleID => {
      return {
        ...resolveMappingMutations(_store)(moduleID),
        ...resolveMappingActions(_store)(moduleID)
      }
    },
  };
}