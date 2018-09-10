export const mapStateC = function(array) {
  let result = {};

  array.forEach(el => result[el] = function() {
    if (!this.$store.state[this.moduleID]) return null;
    return this.$store.state[this.moduleID][el];
  });

  return result;
};

export const mapMutationsC = normalizeNamespace((namespace, mutations) => {
  const res = {};
  normalizeMap(mutations).forEach(({key, val}) => {
    const _val = val;
    res[key] = function mappedMutation(...args) {
      val = this.moduleID + '/' + _val;
      if (namespace && !getModuleByNamespace(this.$store, 'mapMutationsC', this.moduleID + '/')) {
        return
      }
      return this.$store.commit.apply(this.$store, [val].concat(args))
    }
  });
  return res
});

export const mapGettersC = normalizeNamespace((namespace, getters) => {
  const res = {};
  normalizeMap(getters).forEach(({key, val}) => {
    const _val = val;
    res[key] = function mappedGetter() {
      val = this.moduleID + '/' + _val;

      if (namespace && !getModuleByNamespace(this.$store, 'mapGettersC', this.moduleID + '/')) {
        return
      }
      if (process.env.NODE_ENV !== 'production' && !(val in this.$store.getters)) {
        console.error(`[vuex] unknown getter: ${val}`);
        return
      }
      return this.$store.getters[val]
    };
    // mark vuex getter for devtools
    res[key].vuex = true
  });
  return res
});

export const mapActionsC = normalizeNamespace((namespace, actions) => {
  const res = {};
  normalizeMap(actions).forEach(({key, val}) => {
    const _val = val;
    res[key] = function mappedAction(...args) {
      val = this.moduleID + '/' + _val;
      if (namespace && !getModuleByNamespace(this.$store, 'mapActionsC', this.moduleID + '/')) {
        return
      }
      return this.$store.dispatch.apply(this.$store, [val].concat(args))
    }
  });
  return res
});

export const createNamespacedHelpers = (namespace) => ({
  mapState: mapStateC.bind(null, namespace),
  mapGetters: mapGettersC.bind(null, namespace),
  mapMutations: mapMutationsC.bind(null, namespace),
  mapActions: mapActionsC.bind(null, namespace)
});

function normalizeMap(map) {
  return Array.isArray(map)
    ? map.map(key => ({key, val: key}))
    : Object.keys(map).map(key => ({key, val: map[key]}))
}

function normalizeNamespace(fn) {
  return (namespace, map) => {
    if (typeof namespace !== 'string') {
      map = namespace;
      namespace = '';
    } else if (namespace.charAt(namespace.length - 1) !== '/') {
      namespace += '/';
    }
    return fn(namespace, map)
  }
}

function getModuleByNamespace(store, helper, namespace) {
  const module = store._modulesNamespaceMap[namespace];
  if (process.env.NODE_ENV !== 'production' && !module) {
    console.error(`[vuex] module namespace not found in ${helper}(): ${namespace}`)
  }
  return module
}