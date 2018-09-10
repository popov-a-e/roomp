<template>
  <nav>
    <button v-on:click="toggleAdditionalFilters">
      <span v-if='additional'><i class='icon-chevron-left'></i>&nbsp; {{ __('search.back_to_results') }}</span>
      <span v-else>{{__('search.more_filters')}}</span>
    </button>
    <button v-on:click="resetFilters" v-if='!filtersEmpty'>
      {{__('search.reset_filters')}}
    </button>

    <cselect
      class='sort'
      v-on:input="changeSort"
      v-if='!additional'
      :name="__('search.sort_by')"
      :selected="sortBy"
      :options="sortParams">
    </cselect>

    <p v-if='hotels.length > 0'>
      {{ hotelsFoundString }}
    </p>
    <p v-else>
      {{ __('search.hotels_not_found') }}
    </p>
  </nav>
</template>

<script>
  import {mapMutations, mapState, mapGetters} from 'vuex';

  import Cselect from './../../components/Cselect.vue';

  export default {
    components: {
      Cselect
    },
    methods: mapMutations({
      toggleAdditionalFilters: 'Appearance/toggleAdditionalFilters',
      changeSort: 'changeSort',
      resetFilters: 'Filters/resetFilters'
    }),
    computed: {
      ...mapState({
        hotels: state => state.hotels,
        additional: state => state.Appearance.additionalFiltersVisible,
        sortBy: state => state.sortBy,
        sortParams: state => state.BackendData.sortParams
      }),
      ...mapGetters('Filters', ['filtersEmpty']),
      hotelsFoundString() {
        return pluralize('search.hotels_found', this.hotels.length);
      }
    }
  }
</script>

<style lang='scss' scoped>
  .cselect {
    float: right;
  }
</style>
