<template>
  <div class="rates-inner">
    <closed
      :rateID="rateID"
      :roomID="roomID"
    >
    </closed>

    <prices
      v-if="rateVisible"
      v-for="occupancy in occupancies"
      :rateID="rateID"
      :roomID="roomID"
      :occupancy="occupancy"
    ></prices>

    <minstay
      v-if="rateVisible"
      :rateID="rateID"
      :roomID="roomID">
    </minstay>
  </div>
</template>


<script>
  import {mapGetters, mapState} from 'vuex';

  import Closed from './Closed';
  import Prices from './Prices';
  import Minstay from './Minstay';

  export default {
    components: {
      Closed, Prices, Minstay
    },
    props: ['rateID', 'roomID'],
    computed: {
      ...mapState('DashboardTable', ['rates', 'prices', 'rooms']),
      ...mapGetters('DashboardTable', ['dates', 'ratesVisibility']),
      room() {
        return this.rooms[this.roomID];
      },
      occupancies() {
        return Array.from({length: this.room.max_guest_number}).map((v, i) => i + 1);
      },
      rateVisible() {
        return this.ratesVisibility[this.roomID][this.rateID];
      }
    },
  }
</script>