<template>
  <div class='reservations'>
    <h1>{{ __("header.my_bookings") }}</h1>
    <div class='table-container'>
      <table cellpadding='0' cellspacing='0'>
        <tr>
          <th>{{ __("profile.status") }}</th>
          <th>{{ __("profile.code") }}</th>
          <th>{{ __("profile.hotel") }}</th>
          <th>{{ __("profile.checkin") }}</th>
          <th>{{ __("profile.checkout") }}</th>
          <th>{{ __("profile.rooms") }}</th>
          <th>{{ __("profile.nights") }}</th>
          <th>{{ __("profile.sum") }}</th>
        </tr>
        <tr v-for='reservation in reservations'>
          <td>{{reservation.status_name}}</td>
          <td>
            <a v-bind:href='`/r/${reservation.code}?token=${reservation.token}`'>{{reservation.code}}</a>
          </td>
          <td>{{ reservation.hotel }}</td>
          <td>{{ new Date(reservation.from).toVerboseDateString() }}</td>
          <td>{{ new Date(reservation.till).toVerboseDateString() }}</td>
          <td>
            <span v-for='occupancy in reservation.occupancies'>{{ occupancy }}</span>
          </td>
          <td>{{ reservation.nights }}</td>
          <td v-html="toCurrency(reservation.total)"></td>
        </tr>
      </table>
    </div>
  </div>
</template>

<script>
  import {mapState, mapActions, mapMutations, mapGetters} from 'vuex';

  export default {
    data () {
      return {}
    },
    computed: {
      ...mapState(['reservations'])
    },
    methods: {},
    mounted () {
      if (this.reservations.length === 0) this.$store.dispatch('getReservations');
    },
  }
</script>