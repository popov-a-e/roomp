<template>
  <c-select-array class="" style="width: 60px; min-width: 0;" :options="currencies" :selected="currency" :name="__('header.currencies')" @input="changeCurrency"></c-select-array>
</template>
<script>
  import Bus from './../../Bus';
  import {mapState, mapActions} from 'vuex';
  import CSelectArray from '../CSelectArray.vue';

  export default {
    components: {CSelectArray},
    props: ['value'],
    methods: {
      check (value) {
        this.selected = value;
        this.active = !this.active;
      },
      toggle () {
        this.active = !this.active;

        Bus.$emit('click', this._uid);
      },
      country (id) {
        switch (id || this.selected) {
          case 'ru': return 'ru';
          case 'en': return 'gb';
          case 'ch': return 'cn';
        }
      },
      ...mapActions('Header', ['changeCurrency'])
    },
    created () {
      Bus.$on('click', (_uid) => {
        if (_uid !== this._uid) this.active = false;
      });
    },
    computed: {
      ...mapState('Header', ['currencies', 'currency']),
      selected_name () {
        return this.languages[this.selected];
      },
      selected: {
        get () {
          return this.value;
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
  
  label, a {
    display: flex;
    align-items: center;
    justify-content: flex-start;
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