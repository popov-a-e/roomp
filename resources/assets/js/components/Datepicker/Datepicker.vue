<template>
  <div class="datepicker"
       v-bind:style='{height: (height + 2) + "px"}'>

    <transition-group
      class="months"
      v-bind:class="{next: animateNext, prev: animatePrev}"
      name="months"
      tag="div"
      v-bind:style='{height: height + "px"}'
    >
      <month
        class="month current"
        v-bind:key="currentDate.getMonth()"
        :date="currentDate"
        :from='from'
        :till='till'
        :under-mouse='dateUnderMouse'
        :type="type"
        :height='height'
        :loose="loose"
        v-on:input="(e) => $emit('input',e)"
        v-on:msenter='msEnter'
      ></month>
      <month
        class="month next"
        v-bind:key="nextDate.getMonth()"
        :date="nextDate"
        :from='from'
        :till='till'
        :under-mouse='dateUnderMouse'
        :type="type"
        :height='height'
        :loose="loose"
        v-on:input="(e) => $emit('input',e)"
        v-on:msenter='msEnter'
      ></month>
    </transition-group>

    <button
      v-if="loose || (currentDate.getMonth() !== new Date().getMonth() || currentDate.getFullYear() > new Date().getFullYear())"
      class="prev"
      v-on:click="prevMonth"
    >
      <i class='icon-chevron-left'></i>
    </button>
    <button
      class="next"
      v-on:click="nextMonth"
    >
      <i class='icon-chevron-right'></i>
    </button>

    <div
      class='pointer'
      v-bind:style="{
        left: getOffsetLeft() + 'px'
      }"
    ></div>

  </div>
</template>

<script>
  import Month from './Month.vue';

  export default {
    data () {
      return {
        currentDate: new Date(this.from.getTime()),
        animateNext: false,
        animatePrev: false,
        dateUnderMouse: null
      }
    },
    props: {
      from: Date,
      till: Date,
      type: String,
      offsetFrom: Number,
      offsetTill: Number,
      loose: Boolean
    },
    components: {
      Month
    },
    methods: {
      msEnter (date) {
        this.dateUnderMouse = date;
      },
      getOffsetLeft () {
        if (this.type === 'from') {
          return this.offsetFrom;
        } else {
          return this.offsetTill;
        }
      },
      nextMonth() {
        this.animatePrev = false;
        this.animateNext = true;
        this.currentDate = this.nextDate;
      },
      prevMonth () {
        this.animatePrev = true;
        this.animateNext = false;
        this.currentDate = this.prevDate;
      }
    },
    computed: {
      nextDate () {
        let next = new Date(this.currentDate.toDateString());

        next.setDate(1);
        next.setMonth(next.getMonth() + 1);

        return next;
      },

      prevDate () {
        let next = new Date(this.currentDate.toDateString());

        next.setMonth(next.getMonth() - 1);

        return next;
      },
      height () {
        const currentMonthWeeks = new Date(this.currentDate.getFullYear(), this.currentDate.getMonth(), this.currentDate.daysInMonth()).getWeek() - new Date(this.currentDate.getFullYear(), this.currentDate.getMonth(), 1).getWeek() + 1;
        const nextMonthWeeks = new Date(this.nextDate.getFullYear(), this.nextDate.getMonth(), this.nextDate.daysInMonth()).getWeek() - new Date(this.nextDate.getFullYear(), this.nextDate.getMonth(), 1).getWeek() + 1;

        return Math.max(currentMonthWeeks, nextMonthWeeks) > 5 ? 228 : 202;//count > 9 ? 228 : 202;
      }
    }
  }

</script>

<style lang="scss" scoped>
  $month-width: 202px;

  .datepicker {
    width: $month-width * 2 + 2px;
    position: absolute;
    top: calc(100% + 10px);
    left: 0;
    overflow: visible;
    height: $month-width + 2px;
    border: 1px solid #aaa;
    z-index: 100;
    background: white;
    transition: all .3s ease-in-out;

    .month {
      position: absolute;
      width: $month-width;
      background: white;
      height: $month-width;
      padding: 10px;
      transition: all .3s ease-in-out;

      &.current {
        left: 0;
      }

      &.next {
        left: $month-width;
      }
    }
  }

  .pointer {
    position: absolute;
    transform: rotate(-45deg);
    top: -8px;
    left: 20px;
    height: 14px;
    width: 14px;
    background: white;
    z-index: 101;
    border-top: 1px solid #aaa;
    border-right: 1px solid #aaa;
    margin-left: -8px;
  }

  button {
    position: absolute;
    top: 10px;
    -webkit-appearance: none;
    border: none;
    background: white;
    height: 30px;
    width: 30px;
    line-height: 30px;
    left: 10px;
    outline: none !important;
    cursor: pointer;

    &:hover {
      background: #c5c5c5;
    }

    &.next {
      right: 10px;
      left: auto;
    }
  }

  .months {
    position: absolute;
    top: 0;
    left: 0;
    width: $month-width * 2;
    height: $month-width;
    overflow: hidden;
    transition: height .3s ease-in-out;
    background: white;
  }

  .next {
    .months-enter {
      left: $month-width * 2 !important;
    }
    .months-enter-to {
      left: $month-width !important;
    }

    .months-leave {
      left: 0 !important;
    }

    .months-leave-to {
      left: - $month-width !important;
    }
  }

  .prev {
    .months-leave {
      left: $month-width !important;
    }
    .months-leave-to {
      left: $month-width * 2 !important;
    }

    .months-enter {
      left: - $month-width !important;
    }

    .months-enter-to {
      left: 0 !important;
    }
  }
</style>