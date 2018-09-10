<template>
  <div class="occupancy">

    <div style="cursor: pointer;" @click="changeRatesAppearance({roomID, rateID, rateVisible})">
      <div>{{rate.name}}</div>
      <i :class="rateVisible ? 'icon-chevron-down' : 'icon-chevron-right'"></i>
    </div>
    <template v-if="rateVisible">
      <div
        class="guest-number"
        v-for="occupancy in occupancies">
        {{pluralize('common.guests', occupancy)}}

      </div>

      <div
        class="min-stay">
        {{__('extranet.calendar.min_stay')}}
      </div>
    </template>
  </div>
</template>


<script>

  import {mapState, mapMutations, mapGetters} from 'vuex';

  export default {
    props: ['roomID', 'rateID'],
    computed: {
      ...mapState('DashboardTable', ['rooms', 'prices', 'rates', 'ratesAppearance']),
      ...mapGetters('DashboardTable', ['ratesVisibility']),
      room () {
        return this.rooms[this.roomID];
      },
      rateVisible() {
        return this.ratesVisibility[this.roomID][this.rateID];
      },
      rate() {
        return this.rates.where('id', this.rateID);
      },
      occupancies () {
        return Array.from({length: this.room.max_guest_number}).map((v, i) => i + 1);
      },
    },
    methods: {
      ...mapMutations('DashboardTable', ['changeRatesAppearance']),
    }
  }
</script>