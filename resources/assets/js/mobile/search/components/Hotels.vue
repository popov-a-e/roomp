<template>
  <div class="hotels">
    <hotel v-for="hotel in hotels" :hotel='hotel' :from='from' :till='till'></hotel>
    <div v-if='hotels.length === 0' class='empty'>
      <img class='flying-man' src='/img/flyingmachine5.jpg'>
      <div class='text' v-html='__("search.not_found")'>
      </div>
      <div class='controls'>
        <button @click='resetFilters'>{{ __("search.reset_filters") }}</button>
        <button @click='resetPrice'>{{ __("search.reset_prices") }}</button>
      </div>
    </div>
  </div>
</template>

<script>
  import Hotel from './../../../components/Hotel.vue';

  import {mapMutations, mapState, mapGetters} from 'vuex';

  import Bus from './../../../Bus';

  export default {
    data () {
      return {
      }
    },
    components: {
      Hotel
    },
    computed: {
      ...mapGetters({
        hotels: 'hotelsSorted',
      }),
      ...mapState('Filters',['from', 'till'])
    },
    methods: {
      ...mapMutations('Filters', ['resetMap', 'resetPrice', 'resetFilters'])
    },
  }
</script>