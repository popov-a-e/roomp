<template>
  <div v-bind:style='{height: height + "px"}'>
    <h6>{{ name }} {{ year }}</h6>
    <div class="week">
      <div :class='{ch: locale === "ch"}' v-for='day in __("dates.days_short")'>{{day}}</div>
    </div>
    <div class="days">
      <div v-for="date in datesPrev">
      </div>

      <day
        v-on:input='(e) => $emit("input",e)'
        v-on:msenter='(e) => $emit("msenter", e)'
        v-for="date in dates"
        :date='date' :from='from' :till='till' :under-mouse='underMouse' :type='type' :loose="loose"
      >
      </day>
    </div>
  </div>
</template>

<script>
  import Day from './Day.vue';
  import { mapState } from 'vuex';
  
  export default {
    props: {
      date: Date,
      from: Date,
      till: Date,
      underMouse: Date,
      type: String,
      height: Number,
      loose: Boolean
    },
    components: {
      Day
    },
    computed: {
      ...mapState('Header', ['locale']),
      name () {
        return __("dates.full")[this.month];
      },
      month () {
        return this.date.getMonth();
      },
      year() {
        return this.date.getFullYear();
      },
      datesPrev() {
        let d = new Date(this.date.toDateString());

        d.setDate(1);
        while (d.getDay() !== 1) {
          d.setDate(d.getDate() - 1);
        }

        let arr = [];

        while (d.getMonth() !== this.month) {
          arr.push(d.getDate());
          d.setDate(d.getDate() + 1);
        }

        return arr;
      },
      dates () {
        let d = new Date(this.date.toDateString());

        d.setDate(1);

        let arr = [];

        while (d.getMonth() === this.month) {
          arr.push(new Date(d.getTime()));
          d.setDate(d.getDate() + 1);
        }

        return arr;
      }
    }
  }
</script>

<style scoped lang="scss">
  h6 {
    width: 100%;
    text-align: center;
    margin: 0;
    font-size: 14px;
    line-height: 26px;
    color: #304090;
    font-weight: 500;
  }

  .days > *, .week > * {
    float: left;
    height: 26px;
    width: 26px;
    line-height: 26px;
    text-align: center;
    position: relative;
    color: #304090;
    font-size: 14px;
    white-space: nowrap;
    
    &.ch {
      font-size: 13px;
    }
  }
  
  .week {
    color: #304090;
    font-weight: 500;
    font-size: 14px;
  }
</style>