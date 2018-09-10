<template>
  <nav>
    <button @click='showFilters'>{{ __("search.filters") }}</button>
    <button @click='toggleSort'>{{ __("search.sort_by") }}</button>
    <div class='sort' v-if='sortVisible'>
      <cbutton
        v-for='param in sortParams'
        :name='param.name'
        :id='param.id'
        :value='sortBy === param.id'
        @input='changeSort(param.id)'
      >
      </cbutton>
    </div>
  </nav>
</template>

<script>
  import Cbutton from '../../../components/Cbutton.vue';
  import { mapState, mapActions, mapMutations, mapGetters } from 'vuex';

  export default {
    components: {Cbutton},
    data () {
      return {}
    },
    computed: {
      ...mapState({
        sortBy: state => state.sortBy,
        sortParams: state => state.BackendData.sortParams
      }),
      ...mapState('Appearance', {sortVisible: 'sort'})
    },
    methods: {
      ...mapMutations('Appearance', ['showFilters', 'toggleSort']),
      ...mapMutations(['changeSort']),
    }
  }
</script>