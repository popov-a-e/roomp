<template>
  <div class="form">
    <div class='white-overlay'>
      <cselect
        class='cities'
        v-on:input="setCity"
        :name="__('home.select_city')"
        :selected="cityID"
        :options="cities"
      ></cselect>
      <div class='dates-form' v-on:click.prevent.stop='datesClick'>
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
      <guest-selector></guest-selector>
    </div>
    <button class='confirm' v-on:click='confirm'>{{__('home.search') }}</button>
  </div>
</template>

<script>
  import { mapState, mapActions, mapMutations, mapGetters } from 'vuex';
  import Datepicker from './../../components/Datepicker/Datepicker.vue';
  import Cselect from './../../components/Cselect.vue';
  import GuestSelector from '../../components/GuestSelector/GuestSelector.vue';
  import GuestDialog from './GuestsDialog.vue';
  import Bus from './../../Bus';
  
  export default {
    components: {
      Datepicker, Cselect, GuestDialog, GuestSelector
    },
    props: ['params'],
    data () {
      return {
        inputDate: false,
        fromElement: null,
        tillElement: null
      }
    },
    methods: {
      ...mapMutations([
        'setFrom',
        'setTill',
        'initialize',
        'setCity'
      ]),
      ...mapMutations('GuestSelector', ['hideGuestSelector', 'toggleGuestSelector']),
      ...mapActions(['confirm']),
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

        //this.$store.dispatch('updateRooms');
      },
      datesClick () {
        Bus.$emit("click", this._uid);
      }
    },

    computed: {
      ...mapState(['from', 'till', 'cityID', 'adults_number', 'children_number', 'roomNumber', 'cities', 'adultsNumbers', 'guest_selector_visible', 'roomNumbers']),
      ...mapGetters(['fromFormatted', 'tillFormatted', 'fromDayOfWeek', 'tillDayOfWeek', 'nightsFormatted', 'roomNumbersBounded']),
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

      this.initialize(this.params);
    }
  }
</script>