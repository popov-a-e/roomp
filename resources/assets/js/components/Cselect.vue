<template>
  <div class="cselect lang" v-on:click.prevent.stop="toggle">
    <label>
      {{ selected_name }}
      <i class='icon-chevron-down'></i>
    </label>
    <div class="menu" v-if="active">
      <a
        :class='{disabled: disabled(option) }'
        @click.prevent.stop="() => disabled(option) ? '' : check(option.id )"
        v-for="option in options">{{option.name}}</a>
    </div>
  </div>
</template>
<script>
  import Bus from './../Bus';
  import {where} from './../helpers';

  export default {
    data () {
      return {
        active: false
      }
    },
    props: ['options', 'selected', 'name', 'suppress-click'],
    methods: {
      check (value) {
        this.checked = value;
        this.active = !this.active;
      },
      disabled(option) {
        return option.hasOwnProperty("active") && !option.active || option.hasOwnProperty("disabled") && option.disabled;
      },
      toggle () {
        this.active = !this.active;
        
        if (!this.suppressClick) Bus.$emit('click', this._uid);
      }
    },
    created () {
      Bus.$on('click', (_uid) => {
        if (_uid !== this._uid) this.active = false;
      });
    },
    computed: {
      selected_name () {
        if (this.checked === undefined || !this.options.where('id', this.checked)) return this.name;
        return this.options.where('id', this.checked).name;
      },
      checked: {
        get () {
          return this.selected;
        },
        set (value) {
          this.$emit('input', value);
        }
      }
    }
  }
</script>

<style scoped lang='scss'>
  label {
    display: block;
    cursor: pointer;

    &:hover {
      color: #304090;
    }
  }

  i {
    float: right;
    line-height: 30px;
  }

  .cselect {
    position: relative;
  }

  .menu {
    width: calc(100% + 2px);
    top: 100%;
    position: absolute;
    background: #ffffff;
    padding: 10px;
    z-index: 10;
  }

  .menu a {
    width: 100%;
    float: left;
    clear: both;
    cursor: pointer;
    font-size: inherit;
  
    &.disabled {
      color: gray;
      cursor: default;
    }
    
    &:not(.disabled):hover {
      background: rgb(240, 240, 240);
    }
    
    &.disabled:hover {
      background: none;
    }
  }
</style>