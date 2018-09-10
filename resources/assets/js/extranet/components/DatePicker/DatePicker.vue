<template>
  <div class="date-picker">
    <div class="dates" @click.prevent.stop='Bus.$emit("click", _uid)'>
      <a class="from" href="javascript:void(0);"
         v-on:click="toggleDatepicker('from')"
      >{{ from ? from.toVerboseDateString() : __('extranet.date_filter.from') }}</a>
      
      <a class='arrow' href="javascript:void(0);"
         v-on:click="toggleDatepicker('from')">
        <i class='icon-arrow'></i>
      </a>
      
      <a class="till" href="javascript:void(0);"
         v-on:click="toggleDatepicker('till')"
      >{{ till ? till.toVerboseDateString() : __('extranet.date_filter.till') }}</a>
      
      <datepicker
        v-if="inputDate"
        :from="from"
        :till="till"
        :type="inputDate"
        @input="setDates"
      ></datepicker>
    </div>
  </div>
</template>

<script>
  import {mapState, mapActions, mapMutations, mapGetters} from 'vuex';
  import Datepicker from '../../../components/Datepicker/Datepicker.vue';
  import Bus from '../../../Bus';

  export default {
    components: {Datepicker},
    props: ['from', 'till'],
    data () {
      return {
        inputDate: false,
        Bus
      }
    },
    methods: {
      setDates (e) {
        const value = e.value.toISODateString();
        const type = e.type;
        let from = this.from && this.from.toISODateString();
        let till = this.till && this.till.toISODateString();

        if (value < from || value > from && type === 'from') {
          from = e.value;
          till = null;
          this.inputDate = 'till';
        } else {
          from = this.from;
          till = e.value;
          this.inputDate = false;
        }

        this.$emit('input', {from, till});
      },

      toggleDatepicker(dir) {
        this.inputDate = this.inputDate === dir ? false : dir;
      },
    },
    created() {
      Bus.$on('click', (_uid) => {
        if (this._uid !== _uid) this.inputDate = false;
      });
    },
  }
</script>