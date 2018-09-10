<template>
  <label class="v-input">
    <span>{{placeholder}}</span>
    <input :disabled="disabled" :value="value" @input="input"/>
  </label>
</template>

<script>
  export default {
    props: ['value', 'placeholder', 'disabled'],
    methods: {
      input(e) {
        this.$emit('input', e.target.value);
      }
    }
  }
</script>