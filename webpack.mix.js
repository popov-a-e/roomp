const mix = require('laravel-mix');
const fs = require('fs');
"use strict";

const rootModules = ['home','search', 'hotel','reservation','profile'];

let cdn = '';

fs.readFile(`${__dirname}/.env`, {encoding: 'utf-8'}, function(err,data){
  if (!err) {
    let cdn_env = data.split(/[\r\n]/).filter(str => str.match(/^CDN_URL=/))[0];

    if (!cdn_env) return;
    cdn_env = cdn_env.split('=');

    if (cdn_env) cdn = cdn_env;
  }
});

const js = (name, input_path = '', output_path = input_path) => mix.js(`resources/assets/js/${input_path}/${name}.js`, 'public/js/' + output_path);
const css = (name, input_path = '', output_path = input_path) => mix.sass(`resources/assets/sass/${input_path}/${name}.scss`, 'public/css/' + output_path, {data: `$cdn: '${cdn}';`});

rootModules.forEach(name => {
  js(name, name, '');
  css(name, 'index/desktop', '');
});

js('index', 'admin');
css('index', 'admin');

js('index', 'bd');
js('login', 'bd/login', 'bd');
css('index', 'bd');

js('login', 'admin/login', 'admin');
css('login', 'admin');

js('login', 'extranet/login');
css('login', 'extranet');

js('registration', 'extranet/registration', 'extranet');
css('registration', 'extranet');

js('index', 'extranet');
css('index', 'extranet');

js('offer', 'extranet/offer', 'extranet');
css('offer', 'extranet');

const staticPages = ['about', 'static', 'support'];

staticPages.forEach(name => {
  js(name, `static/${name}`, 'static');
  css(name, 'index/desktop/static', 'static');

  js(name, `mobile/static/${name}`, 'mobile/static');
  css(name, 'index/mobile/static', 'mobile/static');
});

css('fonts');

const mobileModules = ['home', 'search', 'hotel', 'profile'];

mobileModules.forEach(name => {
  js(name, 'mobile/' + name, 'mobile');
  css(name, 'index/mobile', 'mobile');
});

js('404', 'errors/404', 'errors');
css('404', 'index/desktop/errors', 'errors');

js('404', 'mobile/errors/404', 'mobile/errors');
css('404', 'index/mobile/errors', 'mobile/errors');

mix.version();
