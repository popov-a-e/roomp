<template>
  <select :value='month' @input="e => $emit('input',e.target.value)">
    <option v-for="(monthName, index) in __('dates.full')" :value="index">{{monthName}}</option>
  </select>
</template>

<script>
  export default {
    props: ['month']
  }
</script>