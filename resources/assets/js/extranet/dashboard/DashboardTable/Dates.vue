<template>
  <div class="dates" :style="{width: (11 + 52 * dates.length) + 'px'}">
    <div class="month" v-for="(days, month) in months" :style="{width: 52 * days + 'px'}">{{days > 1 ? month : ''}}</div>
    <div class="day" v-for="day in dates" :class="{weekend: new Date(day).getDay() === 0 || new Date(day).getDay() === 6}">
      <p>{{ new Date(day).dayOfWeek(true) }}</p>
      <p>{{ new Date(day).getDate() }}</p>
    </div>
  </div>
</template>


<script>
  import {mapGetters} from 'vuex';

  export default {
    computed: mapGetters('DashboardTable', ['dates', 'months']),
  }
</script>