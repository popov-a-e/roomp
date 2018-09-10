<template>
  <div class='filter filter_select' @click.stop='toggle'>
    <input v-model='searchStr' @click.stop='holdFocus' :placeholder='placeholder' :disabled="disabled"/>
    <div class='menu' v-if='active'>
      <a href='javascript:void(0);' v-for='option in optionsFiltered' @click.stop='select(option)'
         :class='{selected: isSelected(option)}'>{{option}}</a>
    </div>
    <button v-on:click.stop='sortChange' :class='{inactive}'><i :class='iClass'></i></button>
  </div>
</template>

<script>
  import Bus from '../../../Bus';
  import {mapState, mapActions, mapMutations, mapGetters} from 'vuex';
  import {escapeRegExp} from '../../../helpers';

  export default {
    data () {
      return {
        active: false,
        searchStr: ''
      }
    },
    props: ['selected', 'options', 'multiple', 'placeholder', 'sortBy', 'name', 'disabled'],
    computed: {
      inactive () {
        return this.sortBy.length !== 2 || this.name !== this.sortBy[0];
      },
      iClass () {
        if (this.inactive) return 'icon-chevron-down';

        return this.sortBy[1] === 'desc' ? 'icon-chevron-down' : 'icon-chevron-top';
      },
      selectedStr() {
        return this.selected instanceof Array ? this.selected.join(', ') : this.selected;
      },
      optionsFiltered() {
        return this.options.filter(option => option && option.match(new RegExp(escapeRegExp(this.searchStr), 'i')))
      }
    },
    methods: {
      holdFocus () {
        this.active = true;
        Bus.$emit('click', this._uid);
      },
      toggle() {
        this.active = !this.active;

        Bus.$emit('click', this._uid);
      },
      select (option) {
        if (!this.multiple) {
          if (this.selected === option) this.$emit('input', null);
          else this.$emit('input', option);
          return;
        }


        let arr = this.selected || [];
        arr = arr.map(e => e);

        let index = arr.indexOf(option);
        if (index >= 0) {
          arr.splice(index, 1);
        } else {
          arr.push(option);
        }
        
        this.$emit('input', arr);
      },
      isSelected (option) {
        if (this.multiple) {
          if (!(this.selected instanceof Array)) return false;

          return this.selected.indexOf(option) >= 0;
        }

        return this.selected === option;
      },
      sortChange () {
        if (!this.inactive) {
          this.$emit('sort', [this.name, this.sortBy[1] === 'desc' ? 'asc' : 'desc']);
          return;
        }

        this.$emit('sort',[this.name, 'desc']);
      }
    },
    created() {
      Bus.$on('click', _uid => {
        if (_uid !== this._uid) this.active = false;
      });
    }
  }
</script>