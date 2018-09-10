<template>
  <div class="progress-bar" @click="$emit('click')">
    <div class="background"></div>
    <div class="selected" :style="'width:'+progress+'%'"></div>
    <span v-if="$slots.default"><slot></slot> %</span>
    <div class="overlay"></div>
  </div>
</template>

<script>
  export default {
    props: ['progress'],
  }
</script>