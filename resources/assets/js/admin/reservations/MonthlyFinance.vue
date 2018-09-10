<template>
  <div class='card monthly-finance' v-if='module_active && hotels'>
    <h2>Отчет за {{ month }}.{{ year }}</h2>

    <div>
      <select v-model="month">
        <option v-for="(key, value) in __('dates.full')" :value="value + 1">{{key}}</option>
      </select>
      <select v-model="year">
        <option v-for="yr in years" :value="yr">{{yr}}</option>
      </select>
    </div>
    <table class="bare" cellspacing="0" cellpadding="0">
      <tr>
        <th>Отель</th>
        <th>Юрлицо</th>
        <th>Оборот</th>
        <th>Кредит</th>
        <th>Дебит</th>
        <th>Booking</th>
        <th>Островок</th>
        <th>Прибыль</th>
      </tr>
      <tr v-for="(hotel, id) in hotels">
        <td><a :href="`#/hotels/${id}/reconcilliation`">{{ hotel.hotel }}</a></td>
        <td>{{ hotel.organization }}</td>
        <td>{{ hotel.revenue.toLocaleString() }}</td>
        <td>{{ hotel.credit.toLocaleString() }}</td>
        <td>{{ hotel.debit.toLocaleString() }}</td>
        <td>{{ hotel.booking.toLocaleString() }}</td>
        <td>{{ hotel.ostrovok.toLocaleString() }}</td>
        <td>{{ (hotel.debit - hotel.booking - hotel.ostrovok).toLocaleString() }}</td>
        <td :class="{docs: true, empty: !hotel.documents_generated}">
          <a :href="`/reconcilliation/doc/${year}/${month}/${id}/report`" class="fa fa-file-excel" style="position: relative;"></a>
          <a :href="`/reconcilliation/doc/${year}/${month}/${id}/act`" class="fa fa-file act" style="position: relative;"><span>A</span></a>
          <a :href="`/reconcilliation/doc/${year}/${month}/${id}/reconciliation_act`" class="fa fa-file reconcilliation"><span>R</span></a>
          <a :href="`/reconcilliation/doc/${year}/${month}/${id}/invoice`" class="fa fa-file bill"><span>B</span></a>
        </td>
        <td>
          <button @click="generate(id)">Сгенерировать</button>
          <button @click="sendEmail(id)">Отправить</button>
        </td>
        <td>
          <i class="fa fa-check" v-if="generatedArray.indexOf(id) >= 0"></i>
          <i class="fa fa-check" v-if="sentArray.indexOf(id) >= 0"></i>
        </td>
      </tr>
      <tr>
        <td colspan="2">ИТОГО</td>
        <td>{{ total.revenue.toLocaleString() }}</td>
        <td>{{ total.credit.toLocaleString() }}</td>
        <td>{{ total.debit.toLocaleString() }}</td>
        <td>{{ total.booking.toLocaleString() }}</td>
        <td>{{ total.ostrovok.toLocaleString() }}</td>
        <td>{{ total.profit.toLocaleString() }}</td>
        <td>
          <button @click="generateAll">Сгенерировать все</button>
          <button @click="sendAll">Отправить все</button>
        </td>
      </tr>
    </table>
  </div>
</template>

<script>
  import MonthlyFinance from '../store/modules/MonthlyFinance';
  
  export default {
    data () {
      return {
        generatedArray: [],
        sentArray: []
      }
    },
    module: {MonthlyFinance},
    methods: {
      initModule() {
        this.load();
      },
      ...MonthlyFinance.mapMethods(),
      generateAll() {
        const hotels = Object.keys(this.hotels);
        let i = 0;
        this.generatedArray = [];

        const fn = () => {
          this.generatedArray.push(hotels[i]);
          this.generate(hotels[i]).then(() => {
            if (i < hotels.length - 1) {
              i++;
              fn();
            }
          });
        };

        fn();
      },
      sendAll() {
        const hotels = Object.keys(this.hotels);
        let i = 0;
        this.sentArray = [];

        const fn = () => {
          this.sentArray.push(hotels[i]);
          this.sendEmail(hotels[i]).then(() => {
            if (i < hotels.length - 1) {
              i++;
              fn();
            }
          });
        };

        fn();
      }
    },
    computed: {
      years() {
        return Array.from({length: (new Date().getFullYear() - 2016 + 1)}).map((v,i) => i + 2016);
      },
      ...MonthlyFinance.mapProps(),
      total() {
        const hotels = Object.values(this.hotels);
        return {
          revenue: hotels.sum('revenue'),
          credit: hotels.sum('credit'),
          debit: hotels.sum('debit'),
          booking: hotels.sum('booking'),
          ostrovok: hotels.sum('ostrovok'),
          profit: hotels.reduce((p,c) => p + (c.debit - c.booking - c.ostrovok), 0)
        }
      }
    },
    created() {
      this.$on('module_activated', () => {
        this.$watch('month', this.load);
        this.$watch('year', this.load);
      });
    }
  }
</script>