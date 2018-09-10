"use strict";

window.__ = (str, values = {}) => {
  const path = str.split('.');

  let tr = window.translations;

  path.forEach(step => tr = tr[step]);

  for (let key in values) {
    if (!values.hasOwnProperty(key)) continue;

    const regex = new RegExp(`:${key}(?:\\?\\((.+)\\))?`);

    //dd (values[key]);
    tr = tr.replace(regex, values[key] || "$1");
  }

  return tr;
};

if (!Object.values) {
  Object.values = function(object) {
    return Object.keys(object).map(key => object[key]);
  }
}

if (!Array.from) {
  Array.from = function(object, func) {
    const len = object.length;
    let arr = [];

    for (let i = 0; i < length; ++i) {
      arr.push(func(object[i], i));
    }

    return arr;
  }
}

const where = function (array, key, value) {
  let filtered = array.filter(function (elem) {
    return elem[key] === value;
  });

  if (filtered.length === 1) return filtered[0];
  if (filtered.length === 0) return -1;

  return filtered;
};

const escapeRegExp = text => text.replace(/[-[\]{}()*+?.,\\^$|#\s]/g, '\\$&');

Date.prototype.daysInMonth = function () {
  const d = new Date(this.getFullYear(), this.getMonth() + 1, 0);
  return d.getDate();
};

window.ceil_to_cents = n => Math.ceil(n * 100) / 100;

window.toCurrency = n => window.__('currency', {n: (1 * Number(n).toFixed(2)).toLocaleString()});

Array.prototype.where = function (key, value) {
  let filtered = this.filter(function (elem) {
    return elem[key] === value;
  });

  if (filtered.length === 1) return filtered[0];
  if (filtered.length === 0) return false;

  return filtered;
};

Array.prototype.unique = function () {
  return this.reduce((p, c) => {
    if (p.indexOf(c) >= 0) return p;

    p.push(c);

    return p;
  }, [])
};

Array.prototype.sum = function(key) {
  return this.reduce((p,c) => p + (key ? c[key] : c), 0);
};


Array.prototype.pluck = function (key, value) {
  if (!value) return this.map(row => row[key]);

  if (value) {
    let result = {};

    this.forEach(row => result[row[key]] = row[value]);

    return result;
  }
};

window.only = function (object, arr) {
  const obj = {};

  Object.keys(object).forEach(key => {
    if (arr.indexOf(key) >= 0) obj[key] = object[key];
  });

  return obj;
};

window.except = function (object, arr) {
  const obj = {};

  Object.keys(object).forEach(key => {
    if (arr.indexOf(key) < 0) obj[key] = object[key];
  });

  return obj;
};

window.objectsEqual = (object1, object2) => {
  if (!(object1 instanceof Object && object2 instanceof Object)) return false;

  for (const key in object1) {
    if (!object1.hasOwnProperty(key)) continue;

    if (object1[key] !== object2[key]) return false;
  }

  return true;
};

Date.prototype.toISODateString = function () {
  let month = this.getMonth() + 1;
  let date = this.getDate();

  if (month < 10) month = '0' + month;
  if (date < 10) date = '0' + date;

  return this.getFullYear() + '-' + month + '-' + date;
};

Date.prototype.getWeek = function () {
  let target = new Date(+this);
  let monday1stWeek = new Date(+this);
  monday1stWeek.setMonth(0);
  monday1stWeek.setDate(1);
  const dayNr = (monday1stWeek.getDay() + 6) % 7;
  monday1stWeek.setDate(monday1stWeek.getDate() - dayNr);
  return Math.floor((this.getTime() - monday1stWeek.getTime()) / 86400000 / 7) + 1;
};

Date.prototype.getRussianMonth = function (short = false) {
  let month = __('dates.full')[this.getMonth()];

  return short ? month.substr(0,3) : month;
};

Date.prototype.getDateAndRussianMonth = function () {
  return this.getDate() + ' '+ __('dates.full')[this.getMonth()].substr(0,3);
};

Date.prototype.toVerboseDateString = function ({short} = {}) {
  const monthName = short ? __('dates.short')[this.getMonth()] : __('dates.dec')[this.getMonth()];

  return `${this.getDate()} ${monthName} ${this.getFullYear()}`;
};

Date.prototype.dayOfWeek = function (short = false) {
  return __('dates.days' + (short ? '_short' : ''))[(this.getDay() || 7) - 1];
};

window.mapObject = (object, func) => {
  let result = {};
  Object.keys(object).map(key => result[key] = func(object[key], key));

  return result;
};

window.filterObject = (object, func) => {
  let result = {};
  Object.keys(object).map(key => {
    if (func(object[key], key)) result[key] = object[key];
  });

  return result;
};

window.image_url = (filename, quality = 1000) => `${window.cloud_storage_root}hotels/photos/${quality}p/${filename}`;
window.map_image_url = (filename) => `${window.cloud_storage_root}hotels/maps/${filename}`;

const serializeArray = function (array) {
  return Object.keys(array).map(function (key) {
    let value = array[key];

    if (value instanceof Date) {
      return key + '=' + value.toISODateString()
    }

    if (Array.isArray(value)) {
      if (value.length === 0) return false;

      let arr = [];
      for (let v of value) {
        arr.push(key + '[]=' + v);
      }

      return arr.join('&');
    }

    if (key === 'map') {
      if (!value.southwest || !array.map_active) return false;
      return `map[southwest][lat]=${value.southwest.lat}&map[southwest][lng]=${value.southwest.lng}&map[northeast][lat]=${value.northeast.lat}&map[northeast][lng]=${value.northeast.lng}&map[zoom]=${value.zoom}`;
    }

    if ((typeof value === "object") && (value !== null)) {
      if (Object.keys(value).length === 0) return false;

      let arr = [];
      let str = '';
      for (let k in value) {
        if (!value.hasOwnProperty(k)) continue;

        arr.push(key + '[' + k + ']=' + value[k]);
      }
      return arr.join('&');
    }

    return key + '=' + value;
  }).filter(function (el) {
    return el !== false;
  }).join('&');
};

const outerHeight = function (el) {
  let height = el.offsetHeight;
  let style = getComputedStyle(el);

  height += parseInt(style.marginTop) + parseInt(style.marginBottom);
  return height;
};

const months = Array.from({length: 12}, (v, k) => k).map(month => {
  const d = new Date(2012, month, 1);

  return {
    id: d.getMonth(),
    name: __('dates.dec')[d.getMonth()]
  }
});

window.htmlEntities = str => {
  return String(str).replace(/&amp;/g, '&').replace(/&lt;/g, '<').replace(/&gt;/g, '>').replace(/&quot;/g, '"');
};

window.pluralize = (tr, n) => {
  const arr = __(tr);

  const getStr = n => {
    if (n === 0 && arr[4]) return arr[4];
    if (n === 1 && arr[3]) return arr[3];

    switch (true) {
      case n > 10 && n < 20:
        return arr[2];
      case n % 10 === 1:
        return arr[0];
      case n % 10 === 2:
      case n % 10 === 3:
      case n % 10 === 4:
        return arr[1];
      default:
        return arr[2];
    }
  };

  const str = getStr(n % 100);

  if (str.indexOf('!*') >= 0) return str.replace('!*', '');
  if (str.indexOf('*') >= 0) return str.replace('*', n);

  return n + ' ' + str;
};

window.dd = console.log;

export {escapeRegExp, where, serializeArray, months, outerHeight};