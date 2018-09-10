<template>
  <div class="closed">
    <cell
      v-for="date in dates"
      :type="'closed'"
      :value="getClosed(date)"
      :rateID="rateID"
      :roomID="roomID"
      :date="date"
      :hasRestrictions="hasRestrictions(date)"
    >

    </cell>
  </div>
</template>


<script>
  import Cell from "./Cell";
  import {mapState, mapGetters} from 'vuex';

  export default {
    components: {
      Cell
    },
    props: ['roomID', 'rateID'],
    computed: {
      ...mapGetters('DashboardTable', ['dates']),
      ...mapState('DashboardTable', ['restrictions', 'availability', 'rooms', 'prices']),
      room (){
        return this.rooms[this.roomID];
      }
    },
    methods: {
      getClosed(date) {
        const restrictions = this.restrictions;
        const availablility = this.availability;
        const restriction = restrictions && restrictions[this.roomID] && restrictions[this.roomID][this.rateID] && restrictions[this.roomID][this.rateID][date];
        const avail = availablility && availablility[this.roomID] && availablility[this.roomID][date];
        const occupancies = Array.from({length: this.room.max_guest_number}).map((v,i) => i + 1);
        const prices =  this.prices && this.prices[this.roomID] && this.prices[this.roomID][this.rateID];

        const hasPrices = occupancies.some(occupancy => prices && prices[occupancy] && prices[occupancy][date]);

        if (Number(avail) === 0) return 'soldout';
        return (restriction ? restriction.closed : false) || !hasPrices;
      },

      hasRestrictions(date) {
        const restrictions = this.restrictions;
        const restriction = restrictions && restrictions[this.roomID] && restrictions[this.roomID][this.rateID] && restrictions[this.roomID][this.rateID][date];

        return restriction ? (restriction.closed_to_arrival || restriction.closed_to_departure || 1 * restriction.maxstay || 1 * restriction.minstay_on_arrival > 1) : null;
      }
    },

  }
</script>