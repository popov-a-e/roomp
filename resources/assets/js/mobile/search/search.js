"use strict";
import '../../bootstrap';

import Bus from './../../Bus';
import store from './store/store';
import { mapState, mapGetters, mapMutations } from 'vuex';

import RoompHeader from '../components/RoompHeader/RoompHeader.vue';
import Filters from './components/Filters.vue';
import Hotels from './components/Hotels.vue';
import GuestOverlay from './components/GuestOverlay.vue';
import GMap from '../../components/GMap/GMap.vue';
import Datepicker from './../components/Datepicker/Datepicker.vue';

const App = new Vue({
  el: 'main',
  data: {
    mounted: false
  },
  store,
  components: {
    RoompHeader, Filters, Hotels, GMap, Datepicker, GuestOverlay
  },
  computed: {
    ...mapState(['link']),
    ...mapState('Appearance', ['mapVisible', 'guest_overlay_visible']),
    ...mapGetters({
      cityData: 'cityData',
      filters: 'filters',
      hotels: 'hotelsSorted'
    }),
    ...mapState('Header/Appearance', ['menuVisible']),
    ...mapState('Appearance', {filtersVisible: 'filters', datepickerVisible: 'datepickerVisible'}),
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
  methods: {
    ...mapMutations(['updateLink']),
    ...mapMutations({
      initializeBackendData: 'BackendData/initialize',
      initializeFilters: 'Filters/initialize'
    }),
  },
  created () {
    this.mounted = true;
    this.$store.dispatch('loadHotels');
    this.updateLink();

    window.addEventListener('resize', () => Bus.$emit('resize'));

    Bus.$on('wheel', (e, _uid) => {
      if (this._uid === _uid) e.preventDefault();
    });

    Bus.$emit('mounted');

    document.addEventListener('touchmove', e => Bus.$emit('mousemove', e.touches[0].pageX, e.touches[0].pageY, e));
    document.addEventListener('touchend', e => Bus.$emit('mouseup', e));
  }
});

export default App;