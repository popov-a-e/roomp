<template>
  <div class="filters" @wheel='wheel' :class='{open: additional}'>
    <general></general>
    
    <secondary></secondary>
    
    <amenities v-show='additional'></amenities>
    
    <filters-nav></filters-nav>
    
    <div class='scrollbar' @mousedown='startScroll' v-if='scrollHeight > 0'
         :style='{height: height + "px", top: top + "px" }'></div>
  </div>
</template>

<script>
  import General from './Filters/General.vue';
  import Secondary from './Filters/Secondary.vue';
  import Amenities from './Filters/Amenities.vue';
  import FiltersNav from './Nav.vue';

  import Bus from '../../Bus';

  import {mapState} from 'vuex';

  export default {
    components: {
      General, Secondary, Amenities, FiltersNav
    },
    data () {
      return {
        scrollHeight: 0,
        offsetHeight: 0,
        scrollTop: 0,
        y: false,
        scrollTopStart: 0
      }
    },
    computed: {
      ...mapState({
        additional: state => state.Appearance.additionalFiltersVisible
      }),
      height() {
        return 100;
      },
      top () {
        return (this.offsetHeight - this.height) * this.scrollTop / this.scrollHeight;
      }
    },
    methods: {
      updateScroll() {
        this.scrollHeight = this.$el.scrollHeight - this.$el.offsetHeight;
        this.offsetHeight = this.$el.offsetHeight;
      },
      wheel (e) {
        let scrollTop = this.scrollTop + e.deltaY;
        scrollTop = Math.max(0, scrollTop);
        scrollTop = Math.min(scrollTop, this.scrollHeight);

        this.scrollTop = scrollTop;
      },
      startScroll(e) {
        this.y = e.pageY;
        this.scrollTopStart = this.scrollTop;
      }
    },
    watch: {
      scrollTop(value) {
        this.$el.scrollTop = value;
      }
    },
    created () {
      window.addEventListener('resize', this.updateScroll);

      Bus.$on('mousemove', (x, y, e) => {
        if (e.which !== 1 || this.y === false) {
          this.y = false;
          return;
        }
        const space = this.offsetHeight - this.height;
        
        let scrollTop = this.scrollTopStart + ((y - this.y) / space )* this.scrollHeight;
        scrollTop = Math.max(0, scrollTop);
        scrollTop = Math.min(scrollTop, this.scrollHeight);

        this.scrollTop = scrollTop;
      });

      Bus.$on('mouseup', e => {
        this.y = false;
      });
    },
    updated () {
      this.updateScroll();
    }
  }
</script>