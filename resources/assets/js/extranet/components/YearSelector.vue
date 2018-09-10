<template>
  <select :value='year' @input="e => $emit('input', e.target.value)">
    <option v-for='yearNumber in years' :value='yearNumber'>{{yearNumber}}</option>
  </select>
</template>

<script>
  export default {
    props: ['year'],
    computed: {
      years() {
        let years = [];
        for (let i = 2017; i <= new Date().getFullYear(); ++i) {
          years.push(i);
        }
        return years;
      }
    },
  }
</script>