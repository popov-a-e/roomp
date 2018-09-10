<template>
  <div class="date_filter">
    <h5>Фильтр даты</h5>
    <select @input="e => switchType(e.target.value)" v-if="$router.history.current.path !== '/reconciliation'">
      <option :value="FilterType.Creation">Создание</option>
      <option :value="FilterType.Arrival">Заезд</option>
      <option :value="FilterType.Departure">Выезд</option>
    </select>

    <div class="dates"  @click.prevent.stop='Bus.$emit("click", _uid)'>
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
  import Datepicker from '../../components/Datepicker/Datepicker.vue';
  import {mapState, mapMutations, mapActions, mapGetters} from 'vuex';
  import Bus from '../../Bus';

  export default {
    components: {Datepicker},
    data() {
      return {
        FilterType: {Arrival: 1, Departure: 2, Creation: 0},
        FilterNames: ['created_at', 'from', 'till'],
        type: 0,
        from: this.from_prop,
        till: this.till_prop,
        dateType: false,
        offsetFrom: 0,
        offsetTill: 0,
        Bus
      }
    },
    props: ['from_prop','till_prop', 'active', 'month'],
    computed: {
      fromFormatted() {
        return this.from ? this.from.toVerboseDateString() : 'с';
      },
      tillFormatted() {
        return this.till ? this.till.toVerboseDateString() : 'по';
      }
    },
    methods: {
      switchType(id) {
        this.type = id;

        this.$emit('input', {
          type: this.FilterNames[this.type], from: this.from, till: this.till
        })
      },
      setDates(e) {
        if (this.active === false) return;
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
          type: this.FilterNames[this.type], from: this.from, till: this.till
        })
      },
      toggleDatepicker(dir) {
        if (this.active === false) return;
        this.dateType = this.dateType === dir ? false : dir;
      }
    },
    mounted() {
      const from = this.$el.querySelector('a.from');
      const till = this.$el.querySelector('a.till');

      this.offsetFrom = from.offsetLeft + from.offsetWidth / 2;
      this.offsetTill = till.offsetLeft + till.offsetWidth / 2;

      Bus.$on('click', (_uid) => {
        if (this._uid !== _uid) this.dateType = false;
      });
    }
  }
</script>