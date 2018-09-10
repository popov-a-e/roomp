/**
 * Created by Thunder on 17.03.2017.
 */

import './../bootstrap';

import Bus from './../Bus';

import store from './store/store.js';

import Root from './components/Root.vue';

import { mapState, mapGetters, mapMutations } from 'vuex';

"use strict";

const App = new Vue({
  el: 'root',
  data: {
    mounted: false
  },
  store,
  components: {
    Root
  },
  computed: {
    ...mapState(['link']),
    ...mapGetters(['filters'])
  },
  watch: {
    filters: {
      handler(val) {
        this.mounted && this.$store.dispatch('loadHotels');
        this.updateLink();
      },
      deep: true
    }
  },
  methods: mapMutations(['updateLink']),
  created () {
    Bus.$on('mounted', () => {
      this.mounted = true;
    });
  }
});

export default App;