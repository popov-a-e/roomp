<template>
  <div class='scrollable_select' @click='toggle'>
    <label>{{ values[value] }}</label>
    <div class='menu' v-if='active' @wheel.prevent.stop='wheel'>
      <a v-for='(val, index) in values' @click='set(index)'>{{ val }}</a>
      <div class='scrollbar' :style='{top:scrollbarTop}'></div>
    </div>
  </div>
</template>

<script>
  import {mapState, mapActions, mapMutations, mapGetters} from 'vuex';

  export default {
    data () {
      return {
        active: false,
        scrollbarTop: 0
      }
    },
    props: ['values', 'get', 'set'],
    computed: {
      value: {
        get () {
          return this.get();
        },
        set (val) {
          this.set(val);
        }
      },
    },
    methods: {
      toggle  () {this.active = !this.active;},
      wheel (e) {
        const menuEl = this.$el.querySelector('.menu');
        menuEl.scrollTop = Math.min(menuEl.scrollHeight - menuEl.offsetHeight, menuEl.scrollTop + e.deltaY);
        this.scrollbarTop = menuEl.scrollTop + (menuEl.offsetHeight - 20) * menuEl.scrollTop / (menuEl.scrollHeight - menuEl.offsetHeight) + 'px';
      }
    }
  }
</script>