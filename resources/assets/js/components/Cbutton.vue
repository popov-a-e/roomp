<template>
  <button type='button' @click="toggle" v-bind:class="{active: value}">
    {{ name }}
  </button>
</template>

<script>
  export default {
    data () {
      return {
        checked: this.value
      }
    },
    props: ['name', 'id', 'value'],
    methods: {
      toggle() {
        this.checked = !this.checked;
        this.$emit('input',{
          key: this.id,
          value: this.checked
        });
      }
    },
    watch: {
      value (val) {
        this.checked = this.value;
      }
    }
  }
</script>

<style scoped lang='scss'>
  button {
    -webkit-appearance: none;
    border: none;
    outline: none !important;
    background: #ececec;
    height: 30px;
    line-height: 30px;
    margin-right: 20px;
    cursor: pointer;
    font-size: 12px;
    
    &:not([disabled]) {
      &:hover {
        background: #e0e0e0;
      }
      
      &.active {
        background: #304090;
        color: white;
  
        &:hover {
          background: #24306d;
        }
      }
    }
    
    &[disabled] {
      background: rgba(236, 236, 236, 0.5);
      
      &.active {
        background: rgba(48, 64, 144, 0.5);
        color: white;
      }
    }
  }

  button.active {
    background: #304090;
    color: white;
  }
</style>