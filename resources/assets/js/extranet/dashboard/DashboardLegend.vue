<template>
  <div class="dashboard-legend">
    <div>
      <cell :hasRestrictions="false" :type="'closed'" :value="false"></cell>
      <span>{{ __('extranet.legend.bookable') }}</span>
    </div>
    <div>
      <cell :hasRestrictions="true" :type="'closed'" :value="false"></cell>
      <span>{{ __('extranet.legend.has_restrictions') }}</span>
    </div>
    <div>
      <cell :hasRestrictions="false" :type="'closed'" :value="'soldout'"></cell>
      <span>{{ __('extranet.legend.no_room') }}</span>
    </div>
    <div>
      <cell :hasRestrictions="false" :type="'closed'" :value="true"></cell>
      <span>{{ __('extranet.legend.closed') }}</span>
    </div>
  </div>
</template>

<script>
  import {mapState, mapActions, mapGetters, mapMutations} from 'vuex';
  import Cell from './DashboardTable/Cell.vue';

  export default {
    components: {Cell}
  }
</script>  