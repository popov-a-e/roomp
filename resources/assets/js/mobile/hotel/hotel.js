"use strict";
import './../../bootstrap';

import Bus from './../../Bus';

import store from './store/store.js';

import Booking from './components/Booking.vue';
import RoomNav from './components/RoomNav/RoomNav.vue'
import RoompHeader from './../components/RoompHeader/RoompHeader.vue';
import GalleryOverlay from './components/Overlay/Overlay.vue';
import PaymentOverlay from './components/PaymentOverlay.vue';
import Guests from './components/Rooms/Picker/Guests.vue';
import Datepicker from './../components/Datepicker/Datepicker.vue';

import Hotel from './../../components/Hotel.vue';
import GMap from './../../components/GMap/GMap.vue';

import {mapState, mapGetters, mapMutations} from 'vuex';


const App = new Vue({
  data: {
    Bus: Bus
  },
  el: 'main',
  store,
  components: {
    Booking, RoomNav, RoompHeader, Hotel, GMap, GalleryOverlay, PaymentOverlay, Guests, Datepicker
  },
  computed: {
    ...mapState('Appearance', ['overlayVisible', 'paymentOverlayVisible', 'bookingVisible', 'datepickerVisible']),
    ...mapState('CurrentRoom', ['picked', 'id'])
  },
  methods: {
    ...mapMutations(['heroimageGalleryOpen']),
    ...mapMutations('Appearance', ['toggleBooking']),
    ...mapMutations('CurrentRoom', ['unpick'])
  },
  created () {
    document.addEventListener('keydown', e => Bus.$emit('keydown', e));
    window.addEventListener('resize', e => Bus.$emit('resize', e));
    document.addEventListener('click', e => Bus.$emit('click', e));
    document.addEventListener('mouseup', e => Bus.$emit('mouseup', e));
    document.addEventListener('mousemove', e => Bus.$emit('mousemove', e.pageX, e.pageY, e));
  },
  mounted () {
    Bus.$emit('mounted');
  },
});


export default App;