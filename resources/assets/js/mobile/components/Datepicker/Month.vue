<template>
  <div class='month'>
    <h6>{{ name }} {{ year }}</h6>
    <div class="days">
      <div v-for="date in datesPrev">
      </div>
      
      <div v-for="(cl,i) in dates"
           @click='click(i + 1)'
           :class='[cl, "day"]'
      >
        {{ i + 1 }}
      </div>
    </div>
  </div>
</template>

<script>
  import Day from './Day.vue';
  import {mapGetters, mapState} from 'vuex';
  export default {
    components: {
      Day
    },
    props: ['year', 'month'],
    methods: {
      click (date) {
        this.$store.commit('Datepicker/setDate', new Date(this.year, this.month, date, 0, 0, 0, 0));
      }
    },
    computed: {
      ...mapState('Datepicker', ['from', 'till']),
      name () {
        return __("dates.full")[this.month];
      },
      date () {
        return new Date(this.year, this.month, 1);
      },
      datesPrev() {
        return Array.from({length: (this.date.getDay() || 7) - 1});
      },
      dates () {
        const today = new Date();
        const from = this.from;
        const till = this.till;
        const thisMonth = this.year === today.getFullYear() && this.month === today.getMonth();
        const date = new Date(this.year, this.month, 1);
        
        return Array.from({length: this.date.daysInMonth()},(v,i) => {
          date.setDate(i + 1);
          if (thisMonth && i + 1 < today.getDate()) return 'inactive';
          if (from && +date === +from || till && +date === +till) return 'picked';
          if (from && till && date > from && date < till) return 'between';
          
          return '';
        });
      }
    }
  }
</script>