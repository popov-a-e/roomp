<template>
  <div class="category-inner">
    <availability
      :roomID="roomID"
    >
    </availability>

    <rates-inner v-for="rate in rates"
                 :rateID="rate.id"
                 :roomID="roomID"
    >
    </rates-inner>
  </div>
</template>


<script>
  import Availability from "./Availability";
  import RatesInner from './RatesInner';

  import {mapState} from "vuex";

  export default {
    components: {
      Availability, RatesInner
    },
    props: ['roomID'],
    computed: {
      ...mapState('DashboardTable', ['rates']),
    }
  }
</script>