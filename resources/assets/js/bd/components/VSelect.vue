<template>
  <label class="v-select">
    <span>{{placeholder}}</span>
    <select @input="input" :value="value">
      <option v-if="select_placeholder" disabled>{{ select_placeholder }}</option>
      <option v-for="option in options" :value="option.id">{{ option.name }}</option>
    </select>
  </label>
</template>

<script>
  export default {
    props: ['value', 'placeholder', 'options', 'select_placeholder'],
    methods: {
      input(e) {
        this.$emit('input', e.target.value);
      }
    }
  }
</script>