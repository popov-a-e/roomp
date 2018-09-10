"use strict";
import './../../../bootstrap';

import Bus from './../../../Bus';

import store from './store/store.js';

import RoompHeader from './../../components/RoompHeader/RoompHeader.vue';

import {mapGetters} from 'vuex';

import array_ru from './../../../static/support/array_ru';
import array_en from './../../../static/support/array_en';

const array = {ru: array_ru, en: array_en};

const load = () => {
  document.addEventListener('scroll', e => {
    Bus.$emit('scroll', document.querySelector('body').scrollTop);

    const scTop = document.querySelector('body').scrollTop;
    const h = document.querySelector('.nav-support').offsetTop + document.querySelector('.nav-support').offsetHeight;

    if (scTop > h) {
      $('.button-up').show();
    } else $('.button-up').hide();
  });

  const App = new Vue({
    data: {
      Bus: Bus,
      buttonVisible: false,
      questions: array[window.locale],
      pattern: ''
    },
    el: 'main',
    store,
    components: {
      RoompHeader
    },
    methods: {
      toTop () {
        document.querySelector('body').scrollTop = 0;
      },
      goTo (_link) {
        console.log(_link);
        const link = _link.replace(/#/, '');
        const body = document.querySelector('body');

        const scrollTopInitial = body.scrollTop;
        const target = document.querySelector(`[name=${link}]`);
        const scrollTopTarget = target.offsetTop - 200;

        const iterations = 20;
        let duration = 200;

        let finishScroll;

        const scrollFinished = new Promise((resolve, reject) => {
          finishScroll = resolve;
        });

        for (let i = 0; i < iterations; ++i) {
          const tick = i / iterations;

          const fn = () => body.scrollTop = scrollTopInitial + tick * (scrollTopTarget - scrollTopInitial);

          setTimeout(fn, tick * duration);
        }

        setTimeout(finishScroll, duration);

        scrollFinished.then(() => {
          duration = 400;

          for (let i = 0; i < iterations; ++i) {
            const tick = i / iterations;
            const opacity = .5 * (iterations - i - 1) / iterations;

            const fn = () => target.style.backgroundColor = `rgba(128,128,128,${opacity})`;

            setTimeout(fn, tick * duration);
          }
        });
      }
    },
    computed: {
      patternRegex (){
        return new RegExp(this.pattern.toLowerCase(), "i");
      }
    },
    mounted () {
      Bus.$on('scroll', scrollTop => {
        const h = document.querySelector('.nav-support').offsetTop + document.querySelector('.nav-support').offsetHeight;

        this.buttonVisible = scrollTop > h;
      });

      Bus.$emit('scroll', document.querySelector('body').scrollTop);
    }
  });
};

document.addEventListener('DOMContentLoaded', load);