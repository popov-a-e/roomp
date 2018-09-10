<template>
  <div class="dashboard">

    <div class="categories-header">
      <div>ID</div>
      <div>{{__('extranet.calendar.room')}}</div>
    </div>

    <div class="dashboard-header">
      <dates></dates>
    </div>

    <dashboard-wrapper>

    </dashboard-wrapper>

    <!--<selection-window></selection-window>-->
  </div>
</template>


<script>
  import Dates from "./DashboardTable/Dates";
  import DashboardWrapper from "./DashboardWrapper";
  import SelectionWindow from '../components/SelectionWindow/SelectionWindow';

  import {mapGetters, mapState} from 'vuex';

  export default {
    components: {
      Dates, DashboardWrapper, SelectionWindow
    },
    computed: {
      ...mapState('DashboardTable', ['scrollLeft']),
      ...mapGetters('DashboardTable', ['dates'])
    },
    watch: {
      scrollLeft: function(value) {
        const el = this.$el.querySelector('.dashboard-header');

        el.scrollLeft = value;
      }
    }
  }
</script>