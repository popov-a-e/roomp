<template>
  <label :class="{'v-input-editable': true}">
    <span>{{name}}</span>
    <input required :value="value" @input="input" :placeholder="placeholder" />
  </label>
</template>


<script>
  import { mapState, mapMutations, mapActions, mapGetters   } from 'vuex'

  export default {
    props: ['name', 'currentKey', 'value', 'placeholder'],
    methods: {
      input(e) {
        this.$emit('input', {value: e.target.value, key: this.currentKey});
      },
    },
  }
</script>