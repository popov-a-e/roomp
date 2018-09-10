<template>
  <div class="filters">
    <date-filter></date-filter>
    <full-filters v-if='additional'></full-filters>
    <nav-bar v-if='!additional'></nav-bar>
  </div>
</template>

<script>
  import NavBar from './NavBar.vue';
  import FullFilters from './FullFilters.vue';
  import {mapState, mapMutations} from 'vuex';
  import DateFilter from './DateFilter.vue';

  export default {
    components: {NavBar, DateFilter,FullFilters},
    props: ['params', 'request', 'maxPrice'],
    computed: {
      ...mapState({
        additional: state => state.Appearance.filters
      })
    },
    methods: {
      ...mapMutations({
        initializeBackendData: 'BackendData/initialize',
        initializeFilters: 'Filters/initialize'
      }),
    },
    created () {
      this.initializeBackendData(this.params);
      this.initializeFilters(this.request);

      this.$store.commit('GuestSelector/seedChildren', this.request.children || []);
      this.$store.commit('GuestSelector/setChildrenNumber', this.request.children_number);
      this.$store.commit('GuestSelector/setAdultNumber', this.request.adult_number);
      this.$store.commit('GuestSelector/setRoomNumber', this.request.room_number);
      this.$store.commit('Filters/setMaxPrice', this.maxPrice);
    }
  }
</script>