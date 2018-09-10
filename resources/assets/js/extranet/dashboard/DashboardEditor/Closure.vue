<template>
  <div class="closure">
    <label>
      <input type="checkbox"
             :indeterminate.prop="restrictions.closed === null"
             v-model="closed">
      <span>{{__('extranet.calendar_changes.closed')}}</span>
    </label>
    <label>
      <input type="checkbox"
             :indeterminate.prop="restrictions.closed_to_arrival === null"
             v-model="closed_to_arrival">
      <span>{{__('extranet.calendar_changes.closed_to_arrival')}}</span>
    </label>
    <label>
      <input type="checkbox"
             :indeterminate.prop="restrictions.closed_to_departure === null"
             v-model="closed_to_departure">
      <span>{{__('extranet.calendar_changes.closed_to_departure')}}</span>
    </label>
  </div>
</template>

<script>
  import {mapState, mapMutations, mapGetters} from 'vuex';

  export default {
    computed: {
      ...mapGetters('DashboardEditor', ['restrictions']),
      closed: {
        get() {
          return this.restrictions.closed;
        },
        set(value) {
          this.setClosed(value);
        }
      },
      closed_to_arrival: {
        get() {
          return this.restrictions.closed_to_arrival;
        },
        set(value) {
          this.setClosedToArrival(value);
        }
      },
      closed_to_departure: {
        get() {
          return this.restrictions.closed_to_departure;
        },
        set(value) {
          this.setClosedToDeparture(value);
        }
      },
    },
    methods: {
      ...mapMutations('DashboardEditor', ['setClosed', 'setClosedToArrival', 'setClosedToDeparture']),
    },
  }
</script>  