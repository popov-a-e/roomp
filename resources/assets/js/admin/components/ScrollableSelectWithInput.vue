<template>
  <div class='scrollable_select' @click.stop.prevent='toggle'>
    <input v-model="filter" />
    <div class='menu' v-if='active' @wheel.prevent.stop='wheel'>
      <a v-for='(val, index) in valuesFiltered' @click='set(index)'>{{ val }}</a>
      <div class='scrollbar' :style='{top:scrollbarTop}'></div>
    </div>
  </div>
</template>

<script>
  import {mapState, mapActions, mapMutations, mapGetters} from 'vuex';
  import Bus from '../../Bus';

  export default {
    data () {
      return {
        active: false,
        scrollbarTop: 0,
        filter: ''
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
      valuesFiltered () {
        let values = {};

        for (let key in this.values) {
          if (!this.values.hasOwnProperty(key)) continue;

          if (this.values[key].toLowerCase().indexOf(this.filter.toLowerCase()) >= 0) values[key] = this.values[key];
        }

        return values;
      }
    },
    watch: {
      value (val) {
        this.filter = this.values[val];
      }
    },
    methods: {
      toggle  () {
        Bus.$emit('click', this._uid);

        this.active = !this.active;
      },
      wheel (e) {
        const menuEl = this.$el.querySelector('.menu');
        menuEl.scrollTop = Math.min(menuEl.scrollHeight - menuEl.offsetHeight, menuEl.scrollTop + e.deltaY);
        this.scrollbarTop = menuEl.scrollTop + (menuEl.offsetHeight - 20) * menuEl.scrollTop / (menuEl.scrollHeight - menuEl.offsetHeight) + 'px';
      }
    },
    created() {
      Bus.$on('click', _uid => {
        if (this._uid !== _uid) this.active = false;
      })
    }
  }
</script>