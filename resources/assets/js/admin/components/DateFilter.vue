<template>
  <div class="date_filter" @click.stop='click'>
    <div class="dates">
      <a class="from" href="javascript:void(0);"
         v-on:click="toggleDatepicker('from')"
      >{{ fromFormatted }}</a>
      <a class='arrow' href="javascript:void(0);"
         v-on:click="toggleDatepicker('from')">
        <i class='icon-arrow'></i>
      </a>
      <a class="till" href="javascript:void(0);"
         v-on:click="toggleDatepicker('till')"
      >{{ tillFormatted }}</a>
      
      <datepicker
        v-if="dateType"
        :from="from"
        :till="till"
        :type="dateType"
        :offset-from="offsetFrom"
        :offset-till="offsetTill"
        :loose="true"
        @input="setDates"
      ></datepicker>
    </div>
  </div>
</template>

<script>
  import Bus from '../../Bus';
  import Datepicker from '../../components/Datepicker/Datepicker.vue';
  import {mapState, mapMutations, mapActions, mapGetters} from 'vuex';

  export default {
    components: {Datepicker},
    data() {
      return {
        from: this.from_prop,
        till: this.till_prop,
        dateType: false,
        offsetFrom: 0,
        offsetTill: 0
      }
    },
    props: ['from_prop','till_prop'],
    computed: {
      fromFormatted() {
        return this.from ? this.from.toVerboseDateString() : 'с';
      },
      tillFormatted() {
        return this.till ? this.till.toVerboseDateString() : 'по';
      }
    },
    methods: {
      setDates(e) {
        if (this.from && e.value.toISODateString() < this.from.toISODateString()) {
          this.from = null;
          this.till = null;
          e.type = this.dateType = 'from';
        }

        if (e.type === 'from') {
          this.from = e.value;
          if (this.till && e.value.toISODateString() >= this.till.toISODateString()) {
            this.till = null;
          }
          this.dateType = 'till';
        } else {
          if (this.from) this.dateType = false;
          this.till = e.value;
        }

        this.$emit('input', {
          from: this.from, till: this.till
        });
      },
      toggleDatepicker(dir) {
        this.dateType = this.dateType === dir ? false : dir;
      },
      click() {
        Bus.$emit('click', this._uid);
      }
    },
    mounted() {
      const from = this.$el.querySelector('a.from');
      const till = this.$el.querySelector('a.till');

      this.offsetFrom = from.offsetLeft + from.offsetWidth / 2;
      this.offsetTill = till.offsetLeft + till.offsetWidth / 2;

      Bus.$on('click', _uid => {
        if (this._uid !== _uid) this.dateType = false;
      });
    }
  }
</script>