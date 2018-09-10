<template>
  <div class='booking'>
    <button class='close' @click='toggleBooking'><i class='icon-close'></i></button>
    <div class='header'>
      <h2>{{ __('hotel.hotel_booking') }}</h2>
    </div>
    <rooms v-if='!rooms_selected'></rooms>
    <book v-if='rooms_selected'></book>
  </div>
</template>

<script>
  import Rooms from './Rooms/Rooms.vue'
  import Book from './Book/Book.vue';
  import Bus from '../../../Bus';
  import {mapMutations, mapState} from 'vuex';
  
  export default {
    props: ['params', 'request', 'hotel_id', 'payment-offline', 'payment-online', 'has-breakfast', 'policy'],
    components: {
      Rooms, Book
    },
    created () {
      if (!this.mounted) {
        this.$store.commit('BackendData/setPolicy', this.policy);
        this.$store.commit('BackendData/initialize', this.params);
        this.$store.commit('Cart/initialize', this.request);
        this.$store.commit('Appearance/checkClass', this.$store.state.BackendData.classes[0]);
        this.$store.commit('initialize', this.hotel_id);
        this.$store.dispatch('updateRooms');
        this.$store.commit('setPaymentOnline', this.paymentOnline);
        this.$store.commit('setPaymentOffline', this.paymentOffline);
        this.$store.commit('setBreakfast', this.hasBreakfast);
        this.mount();
      }
    },
    mounted () {
      const target = this.$el;
    },
    computed: {
      ...mapState(['mounted']),
      rooms_selected () {
        return this.$store.state.Appearance.rooms_selected;
      }
    },
    methods: {
      ...mapMutations('Appearance', ['toggleBooking']),
      ...mapMutations(['mount'])
    }
  }
</script>