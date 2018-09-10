<template>
  <div class='prices card' v-if="module_active && rooms">
    <div class='meta'>
      <p>Даты: </p>
      
      <div class="dates" v-on:click.prevent.stop='datesClick'>
        <a class="from" href="javascript:void(0);"
           v-on:click="toggleDatepicker('from')"
        >{{ from ? from.toISODateString() : 'Начало периода' }}</a>
        <a class='arrow' href="javascript:void(0);"
           v-on:click="toggleDatepicker('from')">
          <i class='icon-arrow'></i>
        </a>
        <a class="till" href="javascript:void(0);"
           v-on:click="toggleDatepicker('till')"
        >{{ till ? till.toISODateString() : 'Конец периода' }}</a>
        <datepicker
          v-if="inputDate"
          :from="from"
          :till="till"
          :type="inputDate"
          :loose="true"
          @input="setDates"
        ></datepicker>
      </div>
    </div>

    <div class="channel-toggle">
      <button :class="{selected: channel === false}" @click="setChannel(false)">Roomp</button>
      <button :class="{selected: channel === true}" @click="setChannel(true)">Channel</button>
    </div>
    <div class="channel-toggle">
      <button :class="{selected: rate_id === 1}" @click="setRateId(1)">Standard</button>
      <button :class="{selected: rate_id === 2}" @click="setRateId(2)">Non-refundable</button>
      <button :class="{selected: rate_id === 3}" @click="setRateId(3)">Last minute</button>
      <button :class="{selected: rate_id === 4}" @click="setRateId(4)">Long stay</button>
    </div>
    
    <div class='data'>
      <div class='rooms'>
        <template v-for='(room, room_id) in rooms'>
          <div class='row'  v-if='prices && prices[room_id] && prices[room_id][rate_id]' v-for='(price, occupancy) in prices[room_id][rate_id]'>
            <div class="room" v-if="occupancy == 1">{{ room }}</div>
            <div class="occupancy">Гостей: {{ occupancy }}</div>
          </div>
        </template>
      </div>
      
      <div class='rows'>
        <div class='row'>
          <div class='cell' v-for='date in dates'>{{ date.getDate() + '/' + (date.getMonth() + 1) }}</div>
        </div>
        <template v-for='(room, room_id) in rooms'>
          <div v-if='prices && prices[room_id] && prices[room_id][rate_id]' class='row' v-for='(price, occupancy) in prices[room_id][rate_id]'>
            <div :class="['cell','day']"
                 v-if="price"
                 v-for='date in dates'
            >{{ price[date.toISODateString()] }}</div>
          </div>
        </template>
      </div>
    </div>
    
    <button v-if='edited' class='save' @click='update'>Сохранить</button>
    <button v-if='edited' class='cancel' @click='cancel'>Отмена</button>

    <div class="selected_rates">
      <p v-for="rate in rates">{{rate.name}}, {{ pluralize('common.guests', rate.guest_number) }}</p>
    </div>
  </div>
</template>

<script>
  import Datepicker from '../../components/Datepicker/Datepicker.vue';
  import Bus from '../../Bus';
  import Prices from '../store/modules/Prices';

  export default {
    components: {Datepicker},
    data () {
      return {
        inputDate: false,
        fromElement: null,
        tillElement: null
      }
    },
    props: ['hotel_id'],
    module: {Prices},
    computed: {
      ...Prices.mapProps(),
    },
    methods: {
      ...Prices.mapMethods(),
      initModule() {
        this.setHotelID(this.hotel_id);

        this.getRooms();
        this.load();

        //this.loadRates();


        this.$watch('channel', this.load);
        this.$watch('rate_id', this.load);
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

        this.load();
      },
      toggleDatepicker(dir) {
        this.inputDate = this.inputDate === dir ? false : dir;
      },
      datesClick() {
        Bus.$emit("click", this._uid);
      }
    },
    created() {
      Bus.$on('click', (_uid) => {
        if (this._uid !== _uid) this.inputDate = false;
      });
    },
    mounted () {
    }
  }
</script>