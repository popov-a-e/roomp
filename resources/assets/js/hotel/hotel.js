"use strict";
import './../bootstrap';

import Bus from './../Bus';

import store from './store/store.js';

import Booking from './components/Booking.vue';
import RoomNav from './components/RoomNav/RoomNav.vue'
import RoompHeader from './../components/RoompHeader/RoompHeader.vue';
import RoompFooter from './../components/RoompFooter/RoompFooter.vue';
import GalleryOverlay from './components/Overlay/Overlay.vue';
import PaymentOverlay from './components/PaymentOverlay.vue';
import Hotel from './../components/Hotel.vue';
import GMap from './../components/GMap/GMap.vue';

import {mapState, mapGetters, mapMutations} from 'vuex';


window.toHotelCurrency = n => window.__('hotel_currency', {n: Number(n).toLocaleString()});

const App = new Vue({
  data: {
    Bus: Bus
  },
  el: 'main',
  store,
  components: {
    Booking, RoomNav, RoompHeader, Hotel, GMap, RoompFooter, GalleryOverlay, PaymentOverlay
  },
  computed: mapState('Appearance', ['overlayVisible', 'paymentOverlayVisible']),
  methods: mapMutations(['heroimageGalleryOpen']),
  created () {
    document.addEventListener('scroll', () => this.$store.dispatch('updateScroll'));
    document.addEventListener('keydown', e => Bus.$emit('keydown', e));
    window.addEventListener('resize', e => Bus.$emit('resize', e));
    document.addEventListener('click', e => Bus.$emit('click', e));
    document.addEventListener('mouseup', e => Bus.$emit('mouseup', e));
    document.addEventListener('mousemove', e => Bus.$emit('mousemove', e.pageX, e.pageY, e));
  }
});
export default App;