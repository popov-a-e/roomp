"use strict";

import router from '../router';
import  {escapeRegExp} from '../../helpers';

const normalizeRoute = route => {
  let name = route.meta.name;

  for (let key in route.params) {
    if (!route.params.hasOwnProperty(key)) continue;

    const regex = new RegExp(escapeRegExp(`$${key}`));

    name = name.replace(regex, route.params[key]);
  }

  return {
    path: route.path,
    name: name,
    id: route.params[route.meta.id_key]
  }
};

const navigateTo = (state, route) => {
  if (!route.meta || (route.meta.id_key && !route.params[route.meta.id_key])) {
    return;
  }

  const resolved = router.resolve(route).route;
  const depth = resolved.matched.length;

  const path = router.resolve(route).route.matched[1].path;

  const r = normalizeRoute(router.resolve(route).route);

  let links = Object.assign({},state.links);
  let pathArray = [];
  let links_path = links;
  let current_path = '/';

  for (let i = 1; i < depth; ++i) {
    const path = router.resolve(route).route.matched[i].path;

    let sub = path.split('/')[path.split('/').length - 1];
    if (sub.indexOf(':') >= 0) sub = resolved.params[sub.replace(':','')];
    if (sub === '') continue;
    pathArray.push(sub);
    current_path += sub;

    if (!links_path[sub]) {
      links_path[sub] = {
        route: normalizeRoute(router.resolve(current_path).route),
        children: {}
      };
    }

    links_path = links_path[sub].children;
    current_path += '/';
  }

  state.links = links;
};


export default {
  namespaced: true,
  state: {
    links: {},
    hotels: {},
    rooms: {},
    promo_codes: {},
    business_developers: {}
  },
  mutations: {
    initRouter: (state) => {
      navigateTo(state, router.currentRoute);
    },

    navigateTo,

    updateLinks: (state, links) => {
      state.links = links
    },

    setMeta: (state, payload) => {
      Vue.set(state[payload.name], payload.id, payload.value);
    }
  },

  actions: {
    routeClose: ({state, commit}, route) => {
      const routePath = route.path || route;
      const resolved = router.resolve(routePath).route;

      let path = routePath.split('/');
      path.splice(0,1);
      const last = path.splice(path.length - 1, 1);

      let links = Object.assign({}, state.links);
      let t = links;

      path.forEach(p => {
        t = t[p].children;
      });

      delete t[last];

      if (router.currentRoute.path === routePath) {
        router.push('/' + path.join('/'));
      }

      commit('updateLinks', links);
    },
    loadMetaData: ({state, commit}, params) => {
      axios.get('/router_meta', {params}).then(response => commit('setMeta', Object.assign(params, {value: response.data})));
    }
  }
}