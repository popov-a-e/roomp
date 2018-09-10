<template>
  <div class='card reservations' v-if='reservations && module_active'>
    <h2>Бронирования</h2>
    <button @click='generateExcel'>
      Экспорт в Excel
    </button>
    
    <a href="#/reservations/create">Создать</a>
    <date-filter :from_prop="from" :till_prop="till" @input="setDateFilter" :active='!isLoading'></date-filter>

    <table cellspacing='0' cellpadding='0'>
      <tr v-if='filters'>
        <th><filter-text :val='filters.id' :name='"id"' :sortBy='sortBy' :placeholder='"#"' v-on:input='setCodeFilter'  v-on:sort='setSort'></filter-text></th>
        <th>
          <filter-select
            :options='statuses'
            :selected='filters.status_name'
            :placeholder='"Статус"'
            :sortBy='sortBy'
            :name='"status_name"'
            @input='setStatusNameFilter'
            :multiple='true'
            @sort='setSort'></filter-select>
        </th>
        <th><filter-text :val='filters.code' :name='"code"' :sortBy='sortBy' :placeholder='"Код брони"' v-on:input='setCodeFilter'  v-on:sort='setSort'></filter-text></th>
        <th v-if="!hotel">
          <filter-select :multiple='true' :options='cities' :selected='filters.city' :name='"city"' :sortBy='sortBy' :placeholder='"Город"' v-on:input='setCityFilter'  v-on:sort='setSort'></filter-select>
        </th>
        <th>
          <filter-select
            :options='channels'
            :multiple='true'
            :selected='filters.channel'
            :name='"channel"' :sortBy='sortBy' :placeholder='"Канал"' v-on:input='setChannelFilter'  v-on:sort='setSort'
          >
          </filter-select>
        </th>
        <th>
          <filter-select
            :options='["онлайн","в отеле"]'
            :multiple='true'
            :selected='filters.online'
            :name='"online"' :sortBy='sortBy' :placeholder='"Статус оплаты"' v-on:input='setOnlineFilter'  v-on:sort='setSort'
          >
          </filter-select>
        </th>
        <th v-if="!hotel">
          <filter-select
            :options='hotels'
            :multiple='true'
            :selected='filters.hotel'
            :name='"hotel"' :sortBy='sortBy' :placeholder='"Отель"' v-on:input='setHotelFilter'  v-on:sort='setSort'
          >
          </filter-select>
        </th>
        <th v-if="hotel">Goody bags</th>

        <th><filter-text :val='filters.guest_name' :name='"guest_name"' :sortBy='sortBy' :placeholder='"Гость"' v-on:input='setGuestNameFilter'  v-on:sort='setSort'></filter-text></th>
        <th><filter-text :val='null' :name='"from"' :sortBy='sortBy' :placeholder='"Заезд"' v-on:input=''  v-on:sort='setSort'></filter-text></th>
        <th>Ночей</th>
        <th>Сумма</th>
        <th>Комментарий</th>
      </tr>
      <tr v-if='reservation && reservation.id' v-on:click='rowClick(reservation.code)' v-for='reservation in reservationsFiltered'>
        <td>{{ reservation.id }}</td>
        <td style='padding-left: 10px;'>{{ reservation.status_name }}</td>
        <td>{{ reservation.code }}</td>
        <td v-if="!hotel" style='padding-left: 10px;'>{{ reservation.city }}</td>
        <td>{{ reservation.channel }}</td>
        <td>{{ reservation.online }}</td>
        <td v-if="!hotel" style='padding-left: 10px;'>{{ reservation.hotel.substr(6) }}</td>
        <td v-if="hotel">{{ reservation.goody_bags }}</td>
        <td>{{ reservation.guest_name }}</td>
        <td>{{ new Date(reservation.from).toVerboseDateString() }} - <br>{{ new Date(reservation.till).toVerboseDateString() }}</td>
        <td>{{ reservation.nights }}</td>
        <td v-html="toCurrency(reservation.total, reservation.hotel_currency)"></td>
        <td>{{ reservation.admin_comment }}</td>
      </tr>
      <tr>
        <td colspan="6">Сумма</td>
        <td>{{ n }} бронирований</td>
        <td>{{ nights }} ночей</td>
        <td>{{ goody_bags }} goody bags</td>
        <td colspan="2">{{ sum }} руб.</td>
      </tr>
    </table>
  </div>
</template>

<script>
  import module from '../store/modules/Reservations';
  import FilterText from './../components/Filters/FilterText.vue';
  import FilterSelect from './../components/Filters/FilterSelect.vue';
  import Reservations from './../store/modules/Reservations';
  import DateFilter from './DateFilter.vue';
  
  export default {
    props: ['hotel'],
    module: {Reservations},
    components: {
      FilterText, DateFilter, FilterSelect
    },
    computed: {
      ...Reservations.mapProps(),
      n () {
        return this.reservationsFiltered.length;
      },
      sum () {
        return this.reservationsFiltered.reduce((p,c) => p + Number(c.total),0);
      },
      nights () {
        return this.reservationsFiltered.reduce((p,c) => p + Number(c.nights),0);
      },
      statuses () {
        return this.reservations.pluck('status_name').unique();
      },
      goody_bags() {
        return this.reservationsFiltered.sum('goody_bags');
      },
      hotels () {
        return this.reservations.pluck('hotel').unique().map(hotel => hotel.substr(6));
      },
      channels() {
        return this.reservations.pluck('channel').unique();
      },
      cities () {
        return this.reservations.pluck('city').unique();
      }
    },
    methods: {
      initModule () {
        const query = {
          hotel_id: this.hotel,
          criteria: this.type
        };

        if (this.from) query.from = this.from.toISODateString();
        if (this.till) query.till = this.till.toISODateString();

        this.load(query);
      },
      setDateFilter(e) {
        this.setType(e.type);
        this.setFrom(e.from);
        this.setTill(e.till);

        const query = {
          hotel_id: this.hotel,
          criteria: this.type
        };

        if (this.from) query.from = this.from.toISODateString();
        if (this.till) query.till = this.till.toISODateString();

        this.load(query);
      },
      ...Reservations.mapMethods()
    },
    created () {
      this.$watch('hotel', e => this.$emit('module_updated', e));
      this.$emit('module_updated');
    }
  }
</script>