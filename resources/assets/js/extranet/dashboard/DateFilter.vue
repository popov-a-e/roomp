<template>
  <div class="filter">
    <h3>{{__('extranet.calendar.filter')}}</h3>
    <date-picker
      :from="from"
      :till="till"
      v-on:input='setDates'></date-picker>
  </div>
</template>


<script>
  import {mapState, mapActions, mapMutations} from 'vuex';
  import DatePicker from '../components/DatePicker/DatePicker';

  export default {
    components: {
      DatePicker
    },
    computed: mapState('DashboardTable', ['from', 'till']),
    methods: {
      ...mapActions('DashboardTable', ['load']),
      ...mapMutations('DashboardTable', ['setDates'])
    },
    watch: {
      from () {
        this.load();
      },
      till () {
        this.load();
      }
    },
    created() {
      this.load();
    }
  }
</script>