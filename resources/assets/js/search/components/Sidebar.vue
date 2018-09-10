<template>
  <div class="sidebar" v-on:wheel='wheel'>
    <filters></filters>

    <hotels v-show='!additional'></hotels>

    <scrollbar></scrollbar>
  </div>
</template>

<script>
  import Filters from './Filters.vue';
  import Hotels from './Hotels.vue';
  import Scrollbar from './Scrollbar.vue';

  import Bus from './../../Bus';

  import {mapMutations, mapState} from 'vuex';

  export default {
    components: {
      Filters, Hotels, Scrollbar
    },
    computed: mapState({
      additional: state => state.Appearance.additionalFiltersVisible
    }),
    methods: mapMutations('Appearance',['wheel','sidebarResize']),
    mounted () {
      this.sidebarResize(this.$el.offsetHeight);

      Bus.$on('resize', () => this.sidebarResize(this.$el.offsetHeight))
    }
  }

</script>