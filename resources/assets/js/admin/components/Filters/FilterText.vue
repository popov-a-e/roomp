<template>
  <div class='filter filter_text'>
    <input v-bind:placeholder='placeholder' v-model='value' :disabled="!!disabled" :class='{bordered: value}'/>
    <button v-on:click='sortChange' :class='{inactive}'><i :class='iClass'></i></button>
    <div v-if='withChoice'></div>
  </div>
</template>

<script>
  import {mapState, mapActions, mapMutations, mapGetters} from 'vuex';

  export default {
    props: ['placeholder', 'withChoice', 'sortBy', 'name', 'val', 'disabled'],
    computed: {
      inactive () {
        return this.sortBy.length !== 2 || this.name !== this.sortBy[0];
      },
      iClass () {
        if (this.inactive) return 'icon-chevron-down';
        
        return this.sortBy[1] === 'desc' ? 'icon-chevron-down' : 'icon-chevron-top';
      },
      value: {
        get () {
          return this.val;
        },
        set(value) {
          this.$emit('input', value);
        }
      }
    },
    methods: {
      sortChange () {
        if (!this.inactive) {
          this.$emit('sort', [this.name, this.sortBy[1] === 'desc' ? 'asc' : 'desc']);
          return;
        }

        this.$emit('sort',[this.name, 'desc']);
      }
    }
  }
</script>