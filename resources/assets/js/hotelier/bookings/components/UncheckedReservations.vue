<template>
  <div class='card bookings'>
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
        <td>{{ (new Date(reservation.from)).toVerboseDateString() }} - <br>{{ (new Date(reservation.till)).toVerboseDateString() }}</td>
        <td>{{ reservation.nights }}</td>
        <td>{{ reservation.total }} руб.</td>
        <td @click.prevent.stop=''>
          <select :value='reservation.show * 1' @input='e => setShow({reservation, value: e.target.value})'>
            <option disabled>Нет данных</option>
            <option value='1'>Заехал</option>
            <option value='0'>Не заехал</option>
          </select>
        </td>
      </tr>
    </table>
    
    <div class='buttons' v-if='shows'>
      <button class='apply' @click='apply'>
        Подтвердить
      </button>
      <button class='reset' @click='reset'>
        Сбросить
      </button>
    </div>
  </div>
</template>

<script>
  import { mapState, mapActions, mapMutations, mapGetters } from 'vuex';
  import FilterText from './../../../admin/components/Filters/FilterText.vue';

  export default {
    data () {
      return {
      }
    },
    components: {FilterText},
    props: ['reservations-unchecked'],
    computed: {
      ...mapGetters('Reservations', ['reservationsFiltered', 'shows']),
      ...mapState('Reservations', ['filters', 'sortBy'])
    },
    methods: {
      ...mapActions('Reservations', ['apply']),
      ...mapMutations('Reservations', ['setShow', 'reset', 'setReservations', 'setStatusFilter', 'setHotelFilter', 'setHotelFilter', 'setCodeFilter', 'setCityFilter', 'setGuestFilter', 'setSort', 'rowClick']),
    },
    created( ) {
      this.setReservations(this.reservationsUnchecked);
    }
  }
</script>