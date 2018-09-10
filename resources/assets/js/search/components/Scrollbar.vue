<template>
  <div
    class='scrollbar'
    v-bind:style='{
      height: height + "px",
      top: top + "px"
    }'
    v-bind:class='{active: pageYStart}'

    v-on:mousedown='mousedown'
    v-if='visible'
  >

  </div>
</template>

<script>
  import Bus from '../../Bus';

  import {mapState, mapGetters, mapMutations} from 'vuex';

  export default {
    data () {
      return {
        scrollTopStart: 0
      }
    },
    methods: {
      mousedown (e) {
        this.setPageYStart(e.pageY);
        this.scrollTopStart = this.scrollTop;
      },
      mouseup () {
        this.setPageYStart(false);
      },
      move(x, y, event) {
        if (event.buttons === 0) this.setPageYStart(false);
        if (!this.pageYStart) return;

        let scrollTop = this.scrollTopStart + this.scrollMax * (y - this.pageYStart) / this.freeSpace;

        scrollTop = Math.max(0, scrollTop);
        scrollTop = Math.min(this.scrollMax, scrollTop);

        this.scroll(scrollTop);
      },
      ...mapMutations({
        scroll: 'Appearance/scroll',
        setPageYStart: 'Appearance/setPageYStart'
      })
    },
    computed: {
      ...mapGetters('Appearance', {
        visible: 'scrollBarVisible',
        height: 'scrollBarHeight',
        top: 'scrollBarTop',
        freeSpace: 'scrollBarFreeSpace',
        scrollMax: 'scrollBarScrollMax'
      }),
      ...mapState('Appearance',{
        scrollTop: 'hotelsScrollTop',
        pageYStart: 'pageYStart'
      })
    },
    mounted () {
      Bus.$on('mousemove', this.move);
      Bus.$on('mouseup', this.mouseup);
    }
  }
</script>