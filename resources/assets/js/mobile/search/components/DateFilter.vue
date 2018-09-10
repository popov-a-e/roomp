<template>
  <div class='dates-filter'>
    <div class='form' v-on:click.prevent.stop='datesClick'>
      <div class='from'>
        <a class="date" href="javascript:void(0);"
           v-on:click="toggleDatepicker('from')"
        >{{ fromFormatted }}</a>
      
        <a class="day" href="javascript:void(0);"
           v-on:click="toggleDatepicker('from')"
        >{{ fromDayOfWeek }}</a>
      </div>
      <span class='nights' v-on:click="toggleDatepicker('from')">
        {{ nightsFormatted }}
      </span>
      <div class='till'>
        <a class="date" href="javascript:void(0);"
           v-on:click="toggleDatepicker('till')"
        >{{ tillFormatted }}</a>
      
        <a class="day" href="javascript:void(0);"
           v-on:click="toggleDatepicker('till')"
        >{{ tillDayOfWeek }}</a>
      </div>
    </div>
  </div>
</template>

<script>
  import {mapState, mapActions, mapMutations, mapGetters} from 'vuex';
  import Bus from '../../../Bus';
  
  export default {
    data () {
      return {
        inputDate: false,
      }
    },
    methods: {
      ...mapMutations('Filters', [
        'setFrom',
        'setTill'
      ]),
      toggleDatepicker(dir) {
        const promise = new Promise(resolve => {
          this.$store.commit('enableDatepicker', {
            from: new Date(this.from),
            till: this.till ? new Date(this.till) : null,
            direction: dir,
            resolve: resolve
          });
        }).then(() => {
          this.setFrom(this.datepickerFrom);
          this.setTill(this.datepickerTill);
        }).catch();
      },
      datesClick() {
        Bus.$emit("click", this._uid);
      }
    },
    computed: {
      ...mapState('Filters', ['from', 'till']),
      ...mapState('Datepicker', {datepickerFrom: 'from', datepickerTill: 'till'}),
      ...mapGetters('Filters', ['fromFormatted', 'tillFormatted', 'fromDayOfWeek', 'tillDayOfWeek', 'nightsFormatted']),
    }
  }
</script>