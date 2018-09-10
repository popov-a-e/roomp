<template>
  <select :value='rate_id' @input='e => setRate(e.target.value)'>
    <option v-for="rate in rates" :value="rate.id">{{rate.name}}</option>
  </select>
</template>


<script>
  import {mapState, mapActions, mapMutations} from 'vuex';

  export default {
    computed: {
      ...mapState('DashboardTable', ['rates']),
      ...mapState('DashboardEditor', ['rate_id']),
    },
    methods: {
      ...mapActions('DashboardEditor', ['clearState']),
      ...mapMutations('DashboardEditor', ['setRate']),
    },
  }
</script>