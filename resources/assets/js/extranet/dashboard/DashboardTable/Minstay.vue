<template>
  <div class="minstay">

    <cell
      v-for="date in dates"
      :roomID="roomID"
      :rateID="rateID"
      :type="'text'"
      :value="getMinstay(date)"
      :date="date"
    >
    </cell>

  </div>
</template>


<script>
  import Cell from "./Cell";

  import {mapGetters, mapState} from 'vuex';

  export default {
    components: {
      Cell
    },
    props: ['rateID', 'roomID'],
    data() {
      return {}
    },
    computed: {
      ...mapState('DashboardTable', ['restrictions']),
      ...mapGetters('DashboardTable', ['dates']),
    },

    methods: {
      getMinstay(date) {
        let restrictions = this.restrictions;
        if (restrictions && restrictions[this.roomID] && restrictions[this.roomID][this.rateID] && restrictions[this.roomID][this.rateID][date]) {
          return restrictions[this.roomID][this.rateID][date].minstay;
        }

        return null;
      }
    },

    created() {

    },
    mounted() {
    }


  }
</script>