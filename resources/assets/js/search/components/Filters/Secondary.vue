<template>
  <div class='secondary-overlay'>
    <div class='secondary' v-bind:style='{marginTop: additional ? 0 : - offset + "px"}'>
      <allotments></allotments>
      <price></price>
    </div>
  </div>
</template>

<script>
  import Allotments from './Allotments.vue';
  import Price from './Price.vue';

  import {mapState, mapGetters, mapMutations} from 'vuex';
  import {outerHeight} from '../../../helpers';

  export default {
    data () {
      return {
        el: null
      }
    },
    components: {
      Allotments, Price
    },
    computed: {
      ...mapState({
        additional: state => state.Appearance.additionalFiltersVisible
      }),
      ...mapGetters({
        offset: 'Appearance/secondaryFiltersOffet'
      }),
    },
    methods: mapMutations('Appearance',['secondaryFiltersResize']),
    mounted () {
      this.secondaryFiltersResize(outerHeight(this.$el.querySelector('.secondary')));
    }
  }
</script>