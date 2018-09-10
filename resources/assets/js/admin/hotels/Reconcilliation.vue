<template>
  <div class='card reconciliation' v-if='module_active'>
    <h2>Сверка</h2>

    <div class='selector'>
      <button @click="showAll" :class="{selected: !filters.active}">
        Все ({{ reservations.length }})
      </button>
      <button @click="showActive" :class="{selected: filters.active}">
        Активные ({{ reservationsActive.length }})
      </button>
    </div>

    <div class='card-content'>
      <div class='date-select'>
        <select v-model='month'>
          <option v-for='(month, i) in __("dates.full")' :value='i'>{{month}}</option>
        </select>
        <select v-model='year'>
          <option v-for='year in years' :value='year'>{{year}}</option>
        </select>
        
        <button @click='apply' v-if='changes'>
          Загрузить
        </button>
      </div>
      
      <table cellspacing='0' cellpadding='0'>
        <tr v-if='filters'>
          <th>
            <filter-text :val='filters.id' :name='"id"' :sortBy='sortBy' :placeholder='"#"' v-on:input='setCodeFilter'
                         v-on:sort='setSort'></filter-text>
          </th>
          <th>
            <filter-text :val='filters.code' :name='"code"' :sortBy='sortBy' :placeholder='"Код брони"'
                         v-on:input='setCodeFilter' v-on:sort='setSort'></filter-text>
          </th>
          <th>
            <filter-text :val='filters.guest_name' :name='"guest_name"' :sortBy='sortBy' :placeholder='"Гость"'
                         v-on:input='setGuestNameFilter' v-on:sort='setSort'></filter-text>
          </th>
          <th>
            <filter-text :val='null' :name='"from"' :sortBy='sortBy' :placeholder='"Заезд"' v-on:input=''
                         v-on:sort='setSort'></filter-text>
          </th>
          <th><filter-select style="padding: 0;" :multiple='true' :options='channels' :selected='filters.channel_name' :name='"channel_name"' :sortBy='sortBy' :placeholder='"Источник"' v-on:input='setChannelNameFilter'  v-on:sort='setSort'></filter-select>
          </th>
          <th><filter-select style="padding: 0;" :multiple='false' :options='statuses' :selected='filters.status_name' :name='"status_name"' :sortBy='sortBy' :placeholder='"Оплата"' v-on:input='setStatusNameFilter'  v-on:sort='setSort'></filter-select>
          </th>
          <th>Ночей</th>
          <th>Сумма</th>
          <th>Roomp Rate</th>
          <th>Подтвержден ли заезд</th>
          <th>Комментарий</th>
        </tr>
        <tr v-if='reservation && reservation.id' v-on:click='edit(reservation.id)'
            v-for='reservation in reservationsFiltered'>
          <td>{{ reservation.id }}</td>
          <td><a @click.stop="" :href="`#/reservations/${reservation.code}`" target="_blank">{{ reservation.code }}</a></td>
          <td>{{ reservation.guest_name }}</td>
          <td>
            {{ new Date(reservation.from).toVerboseDateString()}} - <br>{{ new Date(reservation.tillFix).toVerboseDateString() }}
          
          
          </td>
          <td>{{ reservation.channel_name }}</td>
          <td>{{ reservation.status_name }}</td>
          <td>{{ reservation.nights }}</td>
          <td v-html="toCurrency(reservation.total, reservation.currency)"></td>
          <td v-html="toCurrency(reservation.rate, reservation.currency)"></td>
          <td>{{ noshowConfirmation(reservation) }}</td>
          <td>{{ reservation.admin_comment }}</td>
        </tr>
        <tr>
          <td colspan="4">Сумма</td>
          <td>{{ nights }}</td>
          <td colspan>{{ sum }} руб.</td>
          <td colspan="3">{{ sum_rate }} руб.</td>
        </tr>
      </table>
    </div>
    
    <div class='admin-overlay' v-if='reservation_id' @click='e => edit(null)'>
      <reservation-occupancies @change='apply' :reservation-i-d='reservation_id'>
      </reservation-occupancies>
    </div>
    
    <div class='controls' v-if='!doc_loading'>
      <template v-if='doc.file'>
        <a :href='`/reports/${doc.file}`'>Excel</a>
        <button @click='generateDoc'>Изменить документ</button>
      </template>
      <button v-else @click='generateDoc'>Создать документ</button>
    </div>
    <div v-else>
      Загрузка...
    </div>

    <div class="statistics">
      <h4>Сводка</h4>
      <div>
        <p>Оборот: {{ total.revenue }}</p>
        <p>Кредит: {{ total.credit }}</p>
        <p>Дебет: {{ total.debit }}</p>
        <p>Booking: {{ total.booking }}</p>
        <p>Островок: {{ total.ostrovok }}</p>
      </div>
    </div>
  </div>
</template>

<script>
  import Reconcilliation from '../store/modules/Reconcilliation';
  import FilterText from './../components/Filters/FilterText.vue';
  import FilterSelect from './../components/Filters/FilterSelect.vue';
  import ReservationOccupancies from '../reservations/ReservationOccupancies';

  export default {
    components: {FilterText, ReservationOccupancies, FilterSelect},
    data () {
      return {
        changes: false,
        reservation_id: false
      }
    },
    props: ['hotel_id'],
    module: {Reconcilliation},
    computed: {
      ...Reconcilliation.mapProps(),
      total() {
        const reservations = this.reservationsActive;

        return {
          revenue: reservations.reduce((sum, r) => sum + r.total, 0),
          credit: reservations.filter(r => r.status_name === 'Онлайн' || r.status_name === 'Вирт. карта').reduce((sum, r) => sum + r.total, 0),
          debit: reservations.reduce((sum, r) => sum + (r.total - r.rate), 0),
          booking: reservations.filter(r => r.channel_name === 'Booking').reduce((sum, r) => sum + r.total * this.booking_commission, 0),
          ostrovok: reservations.filter(r => r.channel_name === 'Ostrovok').reduce((sum, r) => sum + r.total * this.ostrovok_commission, 0)
        };
      },
      channels() {
        return this.reservations.pluck('channel_name').unique();
      },
      statuses() {
        return this.reservations.pluck('status_name').unique();
      },
      sum () {
        return this.reservationsFiltered.reduce((p, c) => p + Number(c.total), 0);
      },
      sum_rate() {
        return this.reservationsFiltered.reduce((p, c) => p + Number(c.rate), 0);
      },
      nights () {
        return this.reservationsFiltered.reduce((p, c) => p + Number(c.nights), 0);
      },
      years() {
        let years = [];
        for (let i = 2017; i <= new Date().getFullYear(); ++i) {
          years.push(i);
        }

        return years;
      }
    },
    methods: {
      noshowConfirmation(reservation) {
        if (reservation.noshow === false) return "Подтвержден";
        if (reservation.noshow === true) return "Незаезд";
        if (reservation.status_log[0].status.active) return "Не подтвержден";
        if (!reservation.status_log[0].status.active) return "Отменено";
        return '';
      },
      initModule() {
        this.setHotelID(this.hotel_id);
        this.setDocLoading(true);
        this.apply();
      },
      apply() {
        this.changes = false;
        this.load().then(this.getDocument);
      },
      edit(id) {
        this.reservation_id = id;
      },
      ...Reconcilliation.mapMethods()
    },
    created() {
      this.$once('module_activated', () => {
        this.$watch(function () {
          return new Date(this.year, this.month, 1, 0, 0, 0, 0);
        }, () => {
          this.changes = true;
        });
      });
    }
  }
</script>