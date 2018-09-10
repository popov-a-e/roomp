<template>
  <div class="datepicker">
    <div class='weekdays'>
      <div v-for='day in __("dates.days_short")'>{{ day }}</div>
    </div>
    <div class="months" :style='{height: (height - 10 * 2 - 40 - 30 - 10) + "px"}'>
      <month
        v-for='(month, i) in months'
        :year='month.year'
        :month='month.month'
        
      ></month><!--:style='{marginTop: i === 0 ? marginTop : 0}'-->
    </div>
    <div class='buttons'>
      <button class='apply' @click='apply'>{{ __("common.apply") }}</button>
      <button class='cancel' @click='close'>{{ __("common.cancel") }}</button>
    </div>
  </div>
</template>

<script>
  import Month from './Month.vue';
  import {mapState, mapMutations, mapGetters} from 'vuex';
  export default {
    components: {
      Month
    },
    data (){
      return {
        height: 0
      }
    },
    methods: {
      apply () {
        this.resolve();
        this.close();
      },
      close() {
        this.$store.commit('Appearance/hideDatepicker');
      }
    },
    computed: {
      ...mapState('Datepicker', ['from', 'till', 'direction', 'resolve']),
      ...mapGetters('Datepicker', ['months'])
    },
    mounted () {
      this.height = window.innerHeight;
      window.addEventListener('resize', e => this.height = window.innerHeight);
    }
  }

</script>