<template>
  <div class='card roomp-rates' v-if='module_active'>
    <h2>Коммерческие условия в отеле {{ hotel_id }}</h2>

    <div class='dynamic-rates'>
      <label><span>Дисконт </span><input v-model='discount'/></label>
      <label><span>Маржа </span><input v-model='margin'/></label>
      <button @click="tiePrices">
        <span v-if="tie_state">{{ tie_state }}</span><span v-else>Привязать цены к Roomp Rates</span>
      </button>

      <button @click='update'>Применить изменения</button>
    </div>
  </div>
</template>

<script>
  import RoompRates from '../store/modules/card/RoompRates';
  import {mapState} from 'vuex';

  export default {
    props: ['hotel_id'],
    module: {RoompRates},
    computed: {
      ...RoompRates.mapProps(),
    },
    methods: {
      ...RoompRates.mapMethods(),
      initModule () {
        this.setHotelID(this.hotel_id);
        this.load();
      },
    }
  }
</script>