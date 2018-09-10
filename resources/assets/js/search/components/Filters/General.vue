<template>
  <div class="general">
    <div class='white-overlay'>
      <cselect
        class='cities'
        v-on:input="setCity"
        :name="__('search.city')"
        :selected="cityID"
        :options="cities"
      ></cselect>
      <div class="dates" v-on:click.prevent.stop='datesClick'>
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
          v-if="inputDate"
          :from="from"
          :till="till"
          :type="inputDate"
          :offset-from="offsetFrom"
          :offset-till="offsetTill"
          v-on:input="setDates"
        ></datepicker>
      </div>
      <guest-selector></guest-selector>
    </div>
  </div>
</template>

<script>
  import Datepicker from '../../../components/Datepicker/Datepicker.vue';
  import Cselect from '../../../components/Cselect.vue';
  import GuestSelector from '../../../components/GuestSelector/GuestSelector.vue';

  import Bus from '../../../Bus';

  import {mapState, mapMutations, mapGetters} from 'vuex';

  export default {
    data () {
      return {
        inputDate: false,
        fromElement: null,
        tillElement: null
      }
    },
    components: {
      Cselect, Datepicker, GuestSelector
    },
    methods: {
      ...mapMutations('Filters', [
        'setFrom',
        'setTill',
        'setCity',
        'setGuestNumber',
        'setRoomNumber'
      ]),
      ...mapMutations('GuestSelector', ['hideGuestSelector']),
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
      },
      toggleDatepicker(dir) {
        this.inputDate = this.inputDate === dir ? false : dir;
      },
      datesClick() {
        Bus.$emit("click", this._uid);
      }
    },
    computed: {
      ...mapState({
        cityID: state => state.Filters.city,
        from: state => state.Filters.from ? new Date(state.Filters.from) : null,
        till: state => state.Filters.till ? new Date(state.Filters.till) : null,
        roomNumber: state => state.Filters.room_number,
        cities: state => state.BackendData.cities
      }),
      ...mapState('BackendData', ['cities', 'roomNumbers', 'guestNumbers']),
      ...mapGetters('Filters', ['fromFormatted', 'tillFormatted']),
      ...mapGetters('BackendData', ['roomNumbersBounded']),

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