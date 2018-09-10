<template>
  <div class='availability'>
    <div class='meta'>
      <h1>{{ hotel.ru }}</h1>
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
          :offset-from="offsetFrom"
          :offset-till="offsetTill"
          v-on:input="setDates"
        ></datepicker>
      </div>
    </div>
    
    <div class='data'>
      <div class='rooms'>
        <div class='row' v-for='(room, key) in rooms'>
          <div class='room'>{{room}}</div>
        </div>
      </div>
      
      <div class='rows'>
        <div class='row'>
          <div class='cell' v-for='date in dates'>{{ date.getDate() + '/' + (date.getMonth() + 1) }}</div>
        </div>
        <div class='row' v-for='(room, key) in rooms'>
          <input :class="['cell','day']"
               v-if='availability[key]'
               v-for='date in dates'
               contenteditable='true'
               :value='availability[key][date.toISODateString()]'
               @input='e => setDay({date, room: key, value: e.target.value})' />
        </div>
      </div>
    </div>
    
    <button v-if='edited' class='save' @click='update'>Сохранить</button>
    <button v-if='edited' class='cancel' @click='cancel'>Отмена</button>
  </div>
</template>

<script>
  import {mapState, mapActions, mapMutations, mapGetters} from 'vuex';
  import Datepicker from '../../components/Datepicker/Datepicker.vue';
  import Bus from '../../Bus';

  export default {
    components: {Datepicker},
    data () {
      return {
        inputDate: false,
        fromElement: null,
        tillElement: null
      }
    },
    props: ['rooms'],
    computed: {
      ...mapGetters('Availability', ['dates', 'availability']),
      ...mapState('Availability', ['availability', 'from', 'till', 'edited']),
      ...mapState('Header', ['hotel']),
      offsetFrom () {
        return this.fromElement ? this.fromElement.offsetLeft + this.fromElement.offsetWidth / 2 : 0;
      },
      offsetTill () {
        return this.tillElement ? this.tillElement.offsetLeft + this.tillElement.offsetWidth / 2 : 0;
      }
    },
    methods: {
      ...mapActions('Availability', ['load', 'update']),
      ...mapMutations('Availability', ['setDay', 'setFrom', 'setTill', 'cancel']),
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
      this.load();
      
      Bus.$on('click', (_uid) => {
        if (this._uid !== _uid) this.inputDate = false;
      });
    },
    mounted () {
      this.fromElement = this.$el.querySelector('.dates .from');
      this.tillElement = this.$el.querySelector('.dates .till');

    }
  }
</script>