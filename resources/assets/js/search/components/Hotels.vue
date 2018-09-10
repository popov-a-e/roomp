<template>
  <div class="hotels" @scroll.prevent=''>
    <hotel v-for="hotel in hotels" :hotel='hotel' :from='from' :till='till'></hotel>
    <div v-if='hotels.length === 0' class='empty'>
      <img class='flying-man' src='/img/flyingmachine5.jpg'>
      <div class='text' v-html='__("search.not_found")'>
      </div>
      <div class='controls'>
        <button @click='resetMap'>{{ __("search.reset_map") }}</button>
        <button @click='resetFilters'>{{ __("search.reset_filters") }}</button>
        <button @click='resetPrice'>{{ __("search.reset_prices") }}</button>
      </div>
    </div>
  </div>
</template>

<script>
  import Hotel from './../../components/Hotel.vue';

  import {mapMutations, mapState, mapGetters} from 'vuex';

  import Bus from './../../Bus';

  export default {
    data () {
      return {
        animating: false
      }
    },
    components: {
      Hotel
    },
    computed: {
      ...mapState({
        hotels: 'hotels',
        scrollTop: state => state.Appearance.hotelsScrollTop,
        additional: state => state.Appearance.additionalFiltersVisible
      }),
      ...mapState('Filters', ['from', 'till']),
      ...mapGetters({
        hotels: 'hotelsSorted',
      }),
    },
    methods: {
      ...mapMutations('Appearance', ['hotelsResize']),
      ...mapMutations('Filters', ['resetMap', 'resetPrice', 'resetFilters'])
    },
    watch: {
      scrollTop (value) {
        this.$el.scrollTop = value;
        this.hotelsResize(this.$el);
      },
      additional () {
        this.hotelsResize(this.$el);
      }
    },
    updated () {
      this.hotelsResize(this.$el);
    },
    mounted () {
      Bus.$on('resize', () => this.hotelsResize(this.$el));
    }
  }
</script>