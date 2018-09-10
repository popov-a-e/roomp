<template>
  <div class="prices">
    <h4>{{__('extranet.calendar_changes.price')}}:</h4>

    <label v-for="(number, occupancy) in prices">
      <span>{{pluralize('common.guests', occupancy)}}</span>
      <input :value='prices[occupancy]'
             @input='e => setPrice({price:e.target.value, occupancy})'/>
      <span style="margin-left: 5px;">{{ __('currency', {n: ''}) }}</span>
    </label>
  </div>
</template>


<script>
  import {mapState, mapMutations, mapGetters} from 'vuex';

  export default {
    computed: {
      ...mapGetters('DashboardEditor', ['prices']),
    },
    methods: {
      ...mapMutations('DashboardEditor', ['setPrice']),
    },
  }
</script>