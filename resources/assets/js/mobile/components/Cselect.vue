<template>
  <label class='cselect'>
    <span v-if='valueName || valueName === 0'>{{valueName}}</span>
    <span v-else>{{name}}</span>
    <i class='icon-chevron-down'></i>
    <select @focus='fixHeight' @blur='unfixHeight' v-model='value' @input='e => $emit("input", e.target.value)'>
      <option v-if="name" value='0' disabled>{{ name }}</option>
      <option :disabled='disabled(option)' :value='isIDArray ? option.id : id' v-for='(option, id) in options'>
        {{isIDArray ? option.name : option}}
        
      </option>
    </select>
  </label>
</template>
<script>
  import { mapMutations} from 'vuex'
  
  export default {
    props: ['options', 'selected', 'name'],
    computed: {
      isIDArray() {
        return this.options[0] instanceof Object;
      },
      value: {
        get () {
          return this.selected;
        },
        set (value) {
          this.$emit('input', value);
        }
      },
      valueName () {
        let option;
        if (this.isIDArray) {
          option = this.options.where('id', this.value);
          return option ? option.name : '';
        } else {
          return this.options[this.value];
        }
      }
    },
    methods: {
      disabled(option) {
        return option.hasOwnProperty("active") && !option.active || option.hasOwnProperty("disabled") && option.disabled;
      },
      ...mapMutations('Header/Appearance', ['unfixHeight', 'fixHeight'])
    }
  }
</script>

<style lang='scss' scoped>
  label {
    width: 100%;
    height: 40px;
    line-height: 40px;
    position: relative;
    background: white;
    display: block;
    float: left;
    
    i {
      position: absolute;
      right: 10px;
      top: 0;
      height: 40px;
      line-height: 46px;
      font-size: 14px;
    }
    
    span {
      display: block;
      width: 100%;
      height: 40px;
      line-height: 40px;
      position: relative;
      font-size: 18px;
      padding: 0 10px;
    }
    
    select {
      position: absolute;
      width: 100%;
      height: 100%;
      top: 0;
      left: 0;
      opacity: 0;
    }
  }
</style>