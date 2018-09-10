<template>
  <div class="restrictions">
    <h4>{{__('extranet.calendar_changes.restrictions')}}:</h4>
    <label>
      <span>{{__('extranet.calendar_changes.min_stay')}}</span>
      <input :value="restrictions.minstay"
             :min="rate_id == 3 ? 14 : 0"
             :max="365"
             name="minstay"
             type="number"
             @input='e => setMinstay(e.target.value)'/>
    </label>
    <label>
      <span>{{__('extranet.calendar_changes.max_stay')}}</span>
      <input :value="restrictions.maxstay"
             type="number"
             :min="0"
             :max="365"
             @input='e => setMaxstay(e.target.value)' />
    </label>
    <label>
      <span>{{__('extranet.calendar_changes.min_stay_to_arrival')}}</span>
      <input :value="restrictions.minstay_on_arrival"
             type="number"
             :min="0"
             :max="365"
             @input='e => setMinstayOnArrival(e.target.value)' />
    </label>
  </div>
</template>


<script>
  import {mapState, mapMutations, mapGetters} from 'vuex';

  export default {
    computed: {
      ...mapState('DashboardTable', ['prices']),
      ...mapState('DashboardEditor', ['rate_id']),
      ...mapGetters('DashboardEditor', ['restrictions']),
    },

    methods: {
      ...mapMutations('DashboardEditor', ['setMinstay', 'setMaxstay', 'setMinstayOnArrival', 'setClosed', 'setClosedToArrival', 'setClosedToDeparture']),
    },
  }
</script>