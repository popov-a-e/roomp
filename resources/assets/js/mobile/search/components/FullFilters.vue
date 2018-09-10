<template>
  <div class='full-filters'>
    <allotments></allotments>
    <price></price>
    <amenities></amenities>
    <nav>
      <button @click='toggleFilters'>{{ __("search.apply") }}</button>
      <button @click='resetFilters'>{{ __("search.reset") }}</button>
    </nav>
  </div>
</template>

<script>
  import Allotments from './Allotments.vue';
  import Amenities from './Amenities.vue';
  import Price from './../../../search/components/Filters/Price.vue';
  
  import { mapState, mapActions, mapMutations, mapGetters } from 'vuex';

  export default {
    components: {Allotments, Amenities, Price},
    data () {
      return {}
    },
    computed: {},
    methods: {
      ...mapMutations('Appearance', ['toggleFilters']),
      ...mapMutations('Filters', ['resetFilters'])
    }
  }
</script>