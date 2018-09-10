<template>
  <div
    class="day"
    v-bind:class="{
          inactive, picked, between, hovered
        }"
    v-on:click='() => $emit("click")'
  >
    {{ date.getDate() }}


  </div>
</template>

<script>
  import { mapState } from 'vuex';
  
  export default {
    props: {
      date: Date
    },
    computed: {
      ...mapState('Datepicker', ['from', 'till', 'direction']),
      inactive () {
        return this.date.toISODateString() < new Date().toISODateString();
      },
      picked () {
        return (this.from && this.date.toISODateString() === this.from.toISODateString()) ||
          (this.till && this.date.toISODateString() === this.till.toISODateString());
      },
      between () {
        return (this.from && this.date.toISODateString() > this.from.toISODateString()) &&
          (this.till && this.date.toISODateString() < this.till.toISODateString());
      },
      hovered () {
        if (this.from && this.till || !this.underMouse) return false;

        return (this.date.toISODateString() > this.from.toISODateString()&& this.date.toISODateString() < this.underMouse.toISODateString());
      }
    }
  }
</script>

<style scoped lang='scss'>
  .day {
    cursor: pointer;
    &:hover {
      background: #f2f2f2;
    }

    &.inactive {
      opacity: 0.5;
      cursor: not-allowed;
      &:hover {
        background: none;
      }
    }

    &.picked {
      background: #304090;
      color: white;
    }

    &.between {
      background: #f0f0f0;
    }

    &.hovered {
      background: #f5f5f5;
    }
  }
</style>