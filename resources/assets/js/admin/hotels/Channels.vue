<template>
  <div class='card integrations' v-if='module_active'>
    <h2>Интеграции</h2>

    <div class="switcher">
      <button :class="{active: ota === 'booking'}" @click="selectOTA('booking')">Booking.com</button>
      <button :class="{active: ota === 'ostrovok'}" @click="selectOTA('ostrovok')">Ostrovok.ru</button>
    </div>

    <booking-integration v-if="ota === 'booking'" :hotel-i-d="hotel_id"></booking-integration>
    <ostrovok-integration v-if="ota === 'ostrovok'" :hotel-i-d="hotel_id"></ostrovok-integration>

    <div class="controls">
      <button @click="closeAvailability">
        <span v-if="close_state">{{ close_state }}</span><span v-else>Закрыть доступность</span>
      </button>
      <button @click="syncAvailability">
        <span v-if="availability_state">{{ availability_state }}</span><span v-else>Синхронизировать доступность</span>
      </button>
      <button @click="syncPrices">
        <span v-if="prices_state">{{ prices_state }}</span><span v-else>Синхронизировать цены</span>
      </button>

      <div>
        <input v-model="margin" placeholder="Маржа в %+" />
        <button @click="tiePrices">
          <span v-if="tie_state">{{ tie_state }}</span><span v-else>Привязать цены к Roomp Rates</span>
        </button>
      </div>
    </div>
  </div>
</template>

<script>
  import {mapState, mapActions, mapMutations, mapGetters} from 'vuex';
  import Bus from './../../Bus';
  import Channels from '../store/modules/card/Channels';
  import BookingIntegration from '../channels/BookingIntegration.vue';
  import OstrovokIntegration from '../channels/OstrovokIntegration.vue';

  export default {
    components: {
      BookingIntegration, OstrovokIntegration
    },
    module: {Channels},
    props: ['hotel_id'],
    computed: {
      ...Channels.mapProps(),
    },
    methods: {
      ...Channels.mapMethods(),
      initModule() {
        this.setHotelID(this.hotel_id);
        //this.load();
      },
      selectOTA(str) {
        this.ota = str;
      }
    },
    data() {
      return {
        ota: 'booking'
      }
    }
  }
</script>