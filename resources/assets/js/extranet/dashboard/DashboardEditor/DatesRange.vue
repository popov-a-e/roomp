<template>
  <div class="dates">
    <h4>{{__('extranet.calendar_changes.period')}}:</h4>

    <date-picker
      :from="(new Date(from))"
      :till="(new Date(till))"
      @input='setDates'
    ></date-picker>
  </div>
</template>


<script>
  import DatePicker from '../../components/DatePicker/DatePicker';
  import {mapState, mapMutations, mapActions} from 'vuex';

  export default {
    components: {
      DatePicker
    },
    computed: {
      ...mapState('DashboardEditor', ['from', 'till']),
    },
    methods: {
      ...mapMutations('DashboardEditor', ['setRangeStart', 'setRangeEnd']),
      ...mapMutations('DashboardEditor', ['reset']),
      setDates({from, till}) {
        if (from !== null) this.setRangeStart(from.toISODateString());
        if (till !== null) this.setRangeEnd(till.toISODateString());
      }
    },
  }
</script>