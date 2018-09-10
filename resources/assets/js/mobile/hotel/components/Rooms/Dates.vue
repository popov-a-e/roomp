<template>
  <div class='dates'>
    <div class='placeholder'>
      {{ __('hotel.choose_dates') }}
    </div>
    <div class='form'>
      <div class='from'>
        <a class="date" href="javascript:void(0);"
           @click="toggleDatepicker('from')"
        >{{ fromFormatted }}</a>

        <a class="day" href="javascript:void(0);"
           @click="toggleDatepicker('from')"
        >{{ fromDayOfWeek }}</a>
      </div>
      <span class='nights' @click="toggleDatepicker('from')">
        {{ nightsFormatted }}
      </span>
      <div class='till'>
        <a class="date" href="javascript:void(0);"
           @click="toggleDatepicker('till')"
        >{{ tillFormatted }}</a>

        <a class="day" href="javascript:void(0);"
           @click="toggleDatepicker('till')"
        >{{ tillDayOfWeek }}</a>
      </div>
    </div>
  </div>
</template>

<script>
  import {mapGetters, mapState, mapMutations, mapActions} from 'vuex';
  import Datepicker from './../../../components/Datepicker/Datepicker.vue';

  import Bus from './../../../../Bus';

  export default {
    components: {
      Datepicker
    },
    methods: {
      ...mapActions(['updateRooms']),
      ...mapMutations('Cart', [
        'setFrom',
        'setTill'
      ]),
      toggleDatepicker(dir) {
        const promise = new Promise(resolve => {
          this.$store.commit('enableDatepicker', {
            from: this.from,
            till: this.till,
            direction: dir,
            resolve: resolve
          });
        }).then(() => {
          this.setFrom(this.datepickerFrom);
          this.setTill(this.datepickerTill);

          this.updateRooms();
        }).catch();
      }
    },

    computed: {
      ...mapState('Cart', ['from', 'till']),
      ...mapState('Datepicker', {datepickerFrom: 'from', datepickerTill: 'till'}),
      ...mapGetters('Cart', ['fromFormatted', 'tillFormatted', 'fromDayOfWeek', 'tillDayOfWeek', 'nightsFormatted'])
    }
  }
</script>