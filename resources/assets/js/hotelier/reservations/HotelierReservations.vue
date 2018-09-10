<template>
  <div class='card reservations' v-if='reservations'>
    <h2>Бронирования</h2>
    <table cellspacing='0' cellpadding='0'>
      <tr v-if='filters'>
        <th><filter-text :val='filters.id' :name='"id"' :sortBy='sortBy' :placeholder='"#"' v-on:input='setCodeFilter'  v-on:sort='setSort'></filter-text></th>
        <th><filter-text :val='filters.status_name' :name='"status_name"' :sortBy='sortBy' :placeholder='"Статус"' v-on:input='setStatusFilter' v-on:sort='setSort'></filter-text></th>
        <th><filter-text :val='filters.code' :name='"code"' :sortBy='sortBy' :placeholder='"Код брони"' v-on:input='setCodeFilter'  v-on:sort='setSort'></filter-text></th>
        <th><filter-text :val='filters.city' :name='"city"' :sortBy='sortBy' :placeholder='"Город"' v-on:input='setCityFilter'  v-on:sort='setSort'></filter-text></th>
        <th><filter-text :val='filters.guest_name' :name='"guest_name"' :sortBy='sortBy' :placeholder='"Гость"' v-on:input='setGuestFilter'  v-on:sort='setSort'></filter-text></th>
        <th>Заезд / Выезд</th>
        <th>Ночей</th>
        <th>Сумма</th>
      </tr>
      <tr v-if='reservation && reservation.id' v-on:click='rowClick(reservation.code)' v-for='reservation in reservationsFiltered'>
        <td>{{ reservation.id }}</td>
        <td>{{ reservation.status_name }}</td>
        <td>{{ reservation.code }}</td>
        <td>{{ reservation.city }}</td>
        <td>{{ reservation.guest_name }}</td>
        <td>{{ new Date(reservation.from).toVerboseDateString() }} - <br>{{ new Date(reservation.till).toVerboseDateString() }}</td>
        <td>{{ reservation.nights }}</td>
        <td>{{ reservation.total }} руб.</td>
      </tr>
    </table>
  </div>
</template>

<script>
  import FilterText from './../../admin/components/Filters/FilterText.vue';
  
  import {mapState, mapGetters, mapMutations, mapActions} from 'vuex';
  
  export default {
    computed: {
      ...mapState('Reservations',['sortBy', 'filters', 'reservations']),
      ...mapGetters('Reservations',['reservationsFiltered'])
    },
    methods: {
      ...mapMutations('Reservations',['setStatusFilter', 'setHotelFilter', 'setCodeFilter', 'setCityFilter', 'setGuestFilter', 'setSort', 'rowClick']),
      ...mapActions('Reservations',['load']),
    },
    components: {
      FilterText
    },
    created () {
      this.load();
    }
  }
</script>