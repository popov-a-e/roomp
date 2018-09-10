<template>
  <div class="booking-page">
    <h2>{{__('extranet.booking.header')}}</h2>

    <date-filter :from_prop="from" :till_prop="till" @input="setDates" :active='true'></date-filter>

    <table cellspacing='0' cellpadding='0'>
      <tr>
        <!--<th><filter-text :disabled="true" :val='filters.id' :name='"id"' :sortBy='sortBy' :placeholder='"#"' v-on:input='setCodeFilter'  v-on:sort='setSort'></filter-text></th>-->
        <th style="padding-left: 10px"><filter-text :val='filters.guest_name' :name='"guest_name"' :sortBy='sortBy' :placeholder="__('extranet.booking.guest')" v-on:input='setGuestNameFilter'  v-on:sort='setSort'></filter-text></th>
        <th><filter-text :disabled="true" :val='null' :name='"from"' :sortBy='sortBy' :placeholder="__('extranet.booking.arrival')" v-on:input=''  v-on:sort='setSort'></filter-text></th>
        <th><filter-text :disabled="true" :val='null' :name='"till"' :sortBy='sortBy' :placeholder="__('extranet.booking.departure')" v-on:input=''  v-on:sort='setSort'></filter-text></th>
        <th>
          <filter-select
            :options='statuses'
            :selected='filters.status_name'
            :placeholder="__('extranet.booking.status')"
            :sortBy='sortBy'
            :name='"status_name"'
            @input='setStatusNameFilter'
            :multiple='true'
            @sort='setSort'></filter-select>
        </th>
        <th><filter-text :disabled="true" :val='null' :name='"created_at"' :sortBy='sortBy' :placeholder="__('extranet.booking.created_at')" v-on:input=''  v-on:sort='setSort'></filter-text></th>
        <th><filter-text :disabled="true" :val='null' :name='"occupancies"' :sortBy='sortBy' :placeholder="__('extranet.booking.occupancies')" v-on:input=''  v-on:sort='setSort'></filter-text></th>
        <th><filter-text :disabled="true" :val='null' :name='"total"' :sortBy='sortBy' :placeholder="__('extranet.booking.total')" v-on:input=''  v-on:sort='setSort'></filter-text></th>
        <th><filter-text :disabled="true" :val='null' :name='"rate"' :sortBy='sortBy' :placeholder="__('extranet.booking.rate')" v-on:input=''  v-on:sort='setSort'></filter-text></th>
        <th><filter-text :val='filters.code' :name='"code"' :sortBy='sortBy' :placeholder="__('extranet.booking.code')" v-on:input='setCodeFilter'  v-on:sort='setSort'></filter-text></th>
      </tr>

      <tr v-for='reservation in reservationsFiltered' @click="openReservation(reservation.code)">
        <!--<td style='padding-left: 20px;'>{{ reservation.id }}</td>-->
        <td style="padding-left: 10px">{{ reservation.guest_name }}</td>
        <td>{{ (new Date(reservation.from)).toVerboseDateString() }}</td>
        <td>{{ (new Date(reservation.till)).toVerboseDateString() }}</td>
        <td>
          <div :class='[reservation.status_key, "status"]'>{{ __(`reservation.${reservation.status_key}`) }}</div>
        </td>
        <td :title="reservation.created_at">{{ (new Date(reservation.created_at)).toVerboseDateString() }}</td>
        <td>
          <p v-for="(count, name) in groupRooms(reservation.rooms)">{{ count }} x {{ name }}</p>
        </td>
        <td>{{ toCurrency(reservation.total) }}</td>
        <td>{{ toCurrency(reservation.rate) }}</td>
        <td style='color: #304090'>
          <a>{{ reservation.code }}</a>
        </td>
      </tr>

      <tr v-if="reservationsFiltered.length === 0" class="empty"><td colspan="9" style="text-align: center">{{__('extranet.booking.no_reservations')}}</td></tr>

    </table>

  </div>
</template>


<script>
  import DateFilter from './DateFilter.vue';

  import FilterText from '../../admin/components/Filters/FilterText.vue';
  import FilterSelect from '../../admin/components/Filters/FilterSelect.vue';

  import { mapState, mapMutations, mapActions, mapGetters   } from 'vuex'

  export default {
    components:
      {DateFilter, FilterText, FilterSelect},
    computed: {
      ...mapState('Reservations', ['type', 'from', 'till', 'filters', 'sortBy', 'reservations']),
      ...mapState('Header', ['booking']),
      ...mapGetters('Reservations', ['reservationsFiltered']),

      statuses () {
        return this.reservations.pluck('status_name').unique();
      },
      cities () {
        return this.reservations.pluck('city').unique();
      }
    },
    methods: {
      ...mapMutations('Reservations', ['setType', 'setFrom', 'setTill', 'setCodeFilter', 'setSort', 'setStatusNameFilter', 'setCodeFilter',
                      'setCityFilter', 'setGuestNameFilter']),
      ...mapActions('Reservations', ['load']),
      setDates(e) {
        this.setType(e.type);
        this.setFrom(e.from);
        this.setTill(e.till);

        const query = {
          hotel_id: this.$store.state.Header.hotel.id,
          criteria: this.type
        };

        if (this.from) query.from = this.from.toISODateString();
        if (this.till) query.till = this.till.toISODateString();

        this.load();
      },
      groupRooms(rooms) {
        return rooms.reduce((r, room) => {
          if (r[room]) r[room] ++;
          else r[room] = 1;

          return r;
        }, {});
      },
      openReservation(code) {
        window.open(`/reservation/${code}`, '_blank');
      },
    },
    watch: {
      from (value) {
        this.load();
      },
      till (value) {
        this.load();
      },
      type () {
        this.load();
      }
    },
    created() {
      this.load();
    }
  }
</script>