"use strict";

import '../bootstrap.js';
import mapper from './plugins/mapper';

import Bus from '../Bus';
import router from './router';

import store from './store/store.js';

import AdminHeader from './components/AdminHeader/AdminHeader.vue';
import AdminSidebar from './components/AdminSidebar/AdminSidebar.vue';
import AdminNav from './components/AdminHeader/AdminNav.vue';

window.addEventListener('dragover',function(e){
  Bus.$emit('dragover',e);
  e.preventDefault();
},false);

document.addEventListener('mousemove', e => Bus.$emit('mousemove', e.pageX, e.pageY, e));

document.addEventListener('click', e => Bus.$emit('click', e));
document.addEventListener('mouseup', e => Bus.$emit('mouseup', e));
window.addEventListener('dragenter', e => Bus.$emit('dragenter',e));
window.addEventListener('dragleave', e => Bus.$emit('dragleave', e));

window.addEventListener('drop', e => {
  e.preventDefault();
  return false;
});

Vue.use(mapper);

window.toCurrency = (n, currency) => window.__('currencies.'+currency, {n: Number(n).toLocaleString()});

const App = new Vue({
  data: {
    Bus: Bus
  },
  el: 'main',
  store,
  router,
  components: {
    AdminSidebar, AdminHeader, AdminNav
  },
  created () {

  }
});