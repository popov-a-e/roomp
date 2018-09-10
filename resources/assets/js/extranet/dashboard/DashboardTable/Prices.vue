<template>
  <div class="prices">
    <cell v-for="date in dates"
          :roomID="roomID"
          :rateID="rateID"
          :type="'text'"
          :value="getPrice(date)"
          :date="date">
    </cell>
  </div>
</template>


<script>

  import {mapState, mapGetters} from 'vuex';
  import Cell from "./Cell";

  export default {
    components: {
      Cell
    },
    props: ['roomID', 'rateID', 'occupancy'],
    computed: {
      ...mapState('DashboardTable', ['prices']),
      ...mapGetters('DashboardTable', ['dates'])
    },

    methods: {
      getPrice(date) {
        let prices = this.prices;
        if (prices && prices[this.roomID] && prices[this.roomID][this.rateID] && prices[this.roomID][this.rateID][this.occupancy]) {
          return prices[this.roomID][this.rateID][this.occupancy][date];
        }

        return null;
      }
    },
  }
</script>