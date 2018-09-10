<template>
  <div class='dates'>
    <div class='placeholder'>
      {{ __('hotel.choose_dates') }}
    </div>
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

      <datepicker
        v-if="inputDate"
        :from="from"
        :till="till"
        :type="inputDate"
        :offset-from="offsetFrom"
        :offset-till="offsetTill"
        v-on:input="setDates"
      ></datepicker>
    </div>
  </div>
</template>

<script>
  import {mapGetters, mapState, mapMutations} from 'vuex';
  import Datepicker from './../../../components/Datepicker/Datepicker.vue';

  import Bus from './../../../Bus';

  export default {
    components: {
      Datepicker
    },
    data () {
      return {
        inputDate: false,
        fromElement: null,
        tillElement: null
      }
    },
    methods: {
      ...mapMutations('Cart', [
        'setFrom',
        'setTill'
      ]),
      toggleDatepicker(dir) {
        this.inputDate = this.inputDate === dir ? false : dir;
      },
      setDates (e) {
        if (this.from && e.value.toISODateString() < this.from.toISODateString()) {
          this.setFrom(null);
          this.setTill(null);
          e.type = this.inputDate = 'from';
        }

        if (e.type === 'from') {
          this.setFrom(e.value);
          if (this.till && e.value.toISODateString() >= this.till.toISODateString()) {
            this.setTill(null);
          }
          this.inputDate = 'till';
        } else {
          if (this.from) this.inputDate = false;
          this.setTill(e.value);
        }

        this.$store.dispatch('updateRooms');
      },
      datesClick () {
        Bus.$emit("click", this._uid);
      }
    },

    computed: {
      ...mapState('Cart', ['from', 'till']),
      ...mapGetters('Cart', ['fromFormatted', 'tillFormatted', 'fromDayOfWeek', 'tillDayOfWeek', 'nightsFormatted']),
      offsetFrom () {
        return this.fromElement.offsetLeft + this.fromElement.offsetWidth / 2;
      },
      offsetTill () {
        return this.tillElement.offsetLeft + this.tillElement.offsetWidth / 2;
      }
    },
    created () {
      Bus.$on('click', (_uid) => {
        if (this._uid !== _uid) this.inputDate = false;
      });
    },
    mounted () {
      this.fromElement = this.$el.querySelector('.from');
      this.tillElement = this.$el.querySelector('.till');
    }
  }
</script>