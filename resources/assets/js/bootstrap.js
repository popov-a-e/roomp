"use strict";

let Vue = window.Vue = require('vue');
let Vuex = window.Vuex = require('vuex');

require('./helpers.js');

Vue.use(Vuex);

require('es6-promise').polyfill();

const Translator = {};

Translator.install = function (Vue, options) {
  Vue.prototype.__ = window.__;
  Vue.prototype.image_url = window.image_url;
  Vue.prototype.map_image_url = window.map_image_url;
  Vue.prototype.pluralize = window.pluralize;
  Vue.prototype.toCurrency = (...args) => window.toCurrency.apply(window, args);
  Vue.prototype.toHotelCurrency = (...args) => window.toHotelCurrency ? window.toHotelCurrency.apply(window, args) : false;
};

String.prototype.toCamelCase = function () {
  return this.split('_').map(word => word[0].toUpperCase() + word.substr(1)).join('');
};

Vue.use(Translator);

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common = {
  'X-CSRF-TOKEN': window.Laravel.csrfToken,
  'X-Requested-With': 'XMLHttpRequest'
};

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from "laravel-echo"

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: 'your-pusher-key'
// });

