<template>
  <div class="reconciliation-page">
    <h2>{{__('extranet.reconciliation.header')}}</h2>

    <div class="month-and-year-dates-selector">
      <h5>{{__('extranet.reconciliation.period_of_reconciliation')}}</h5>
      <month-selector :month="month" @input="setMonth"></month-selector>
      <year-selector :year="year" @input="setYear"></year-selector>
    </div>


    <table cellspacing='0' cellpadding='0'>
      <tr>
        <!--<th>#</th>-->
        <th style="padding-left: 10px"><filter-text :val='filters.guest_name' :name='"guest_name"' :sortBy='sortBy' :placeholder="__('extranet.reconciliation.guest')" v-on:input='setGuestNameFilter'  v-on:sort='setSort'></filter-text></th>
        <th>{{ __('extranet.reconciliation.arrival') }}</th>
        <th>{{ __('extranet.reconciliation.departure') }}</th>
        <th class="status">
          <filter-select
            :options='statuses'
            :selected='filters.status_name'
            :placeholder="__('extranet.booking.status')"
            :sortBy='sortBy'
            :name='"status_name"'
            @input='setStatusNameFilter'
            :multiple='true'
            :disabled='true'
            @sort='setSort'></filter-select>
        </th>
        <th>{{ __('extranet.reconciliation.created_at') }}</th>
        <th>{{ __('extranet.reconciliation.occupancies') }}</th>
        <th>{{ __('extranet.reconciliation.total') }}</th>
        <th>{{ __('extranet.reconciliation.rate') }}</th>
        <th>{{ __('extranet.reconciliation.code') }}</th>
      </tr>

      <tr v-for='reservation in reservationsFiltered' @click="openReservation(reservation.code)">
        <!--<td style='padding-left: 20px;'>{{ reservation.id }}</td>-->
        <td style="padding-left: 10px">{{ reservation.guest_name }}</td>
        <td>{{ new Date(reservation.from).toVerboseDateString() }}</td>
        <td>{{ new Date(reservation.till).toVerboseDateString() }}</td>
        <td class="status">
          <div :class="`status ${reservation.status_key}`">{{ __(`reservation.${reservation.status_key}`) }}</div>
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

      <tr v-if="reservations.length>0">
        <td colspan="5"></td>
        <td style="color: #304090; font-weight: bold">{{__('extranet.reconciliation.total_sum')}}</td>
        <td style="color: #304090;">{{toCurrency(totalSum)}}</td>
        <td style="color: #304090;">{{toCurrency(totalRateSum)}}</td>
        <td></td>
      </tr>

      <tr v-if="reservations.length === 0" class="empty">
        <td colspan="9" style="text-align: center">{{__('extranet.reconciliation.no_reconciliations_records')}}</td>
      </tr>

    </table>


  </div>
</template>


<script>
  import FilterText from '../../admin/components/Filters/FilterText.vue';
  import FilterSelect from '../../admin/components/Filters/FilterSelect.vue';
  import YearSelector from '../components/YearSelector';
  import MonthSelector from '../components/MonthSelector';


  import { mapState, mapMutations, mapActions, mapGetters   } from 'vuex'

  export default {
    components:
      {FilterText, FilterSelect, YearSelector, MonthSelector},
    computed: {
      ...mapState('Reconciliation', ['type', 'from', 'till', 'filters', 'sortBy', 'reservations', 'month', 'year']),
      ...mapGetters('Reconciliation', ['totalSum', 'totalRateSum', 'reservationsFiltered']),

      statuses () {
        return this.reservations.pluck('status_name').unique();
      },
      cities () {
        return this.reservations.pluck('city').unique();
      }
    },
    methods: {
      ...mapMutations('Reconciliation', ['setSort', 'setStatusNameFilter', 'setCodeFilter', 'setGuestNameFilter', 'setMonth', 'setYear']),
      ...mapActions('Reconciliation', ['load']),
      groupRooms(rooms) {
        return rooms.reduce((r, room) => {
          if (r[room]) r[room] ++;
          else r[room] = 1;

          return r;
        }, {});
      },
      openReservation(code) {
        window.open(`/reservation/${code}`, '_blank');
      }
    },
    watch: {
      year () {this.load()},
      month() {this.load()}
    },
    created() {
      this.load();
    }
  }
</script>