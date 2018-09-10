<template>
  <form class="dashboard-editor" @click.stop="" @keyup.esc="hide" @submit.prevent="save">
    <h2>{{__('extranet.calendar_changes.header')}}</h2>
    <button type="button" class='close' @click="hide"><i class="icon-close"></i></button>
    
    <div>
      <dates-range></dates-range>
      <availability></availability>
      
      <div class='rate'>
        <h4>{{ rate.name }} - {{__('extranet.calendar_changes.price_and_restricitons')}}</h4>
        <!--<rate-selector></rate-selector>-->

        <closure></closure>
        <prices></prices>
        <restrictions></restrictions>
      </div>
      
      <div class="controls">
        <button class="cancel" :disabled="loading" type="button" @click="hide">{{__('extranet.calendar_changes.cancel')}}</button>
        <button class="apply" :disabled="loading" type="submit">
          <template v-if="!loading">{{__('extranet.calendar_changes.save')}}</template>
          <i v-else class="fa fa-circle-notch"></i>
        </button>
      </div>
    </div>
  </form>
</template>

<script>
  import DatesRange from './DashboardEditor/DatesRange';
  import Availability from './DashboardEditor/Availability';
  import Prices from './DashboardEditor/Prices';
  import Restrictions from './DashboardEditor/Restrictions';
  import RateSelector from './DashboardEditor/RateSelector';
  import Bus from '../../Bus';
  import Closure from './DashboardEditor/Closure.vue';

  import {mapActions, mapState, mapMutations} from 'vuex';

  export default {
    components: {
      Availability, Prices, Restrictions, DatesRange, RateSelector, Closure
    },
    computed: {
      ...mapState('DashboardEditor', ['loading', 'rate_id']),
      ...mapState('DashboardTable', ['rates']),
      rate() {
        return this.rates.where('id', this.rate_id);
      }
    },
    methods: {
      ...mapActions('DashboardEditor', ['save']),
      ...mapMutations('DashboardEditor', ['hide']),
    },
    created() {
      Bus.$on('keydown', e => {
        if (e.keyCode === 27) this.hide();
      });

      dd (this.rate);
    }
  }

</script>