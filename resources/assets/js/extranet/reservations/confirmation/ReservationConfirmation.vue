<template>
  <div class="booking-confirmation">
    <h2>{{__('extranet.booking-confirmation.header')}}</h2>
    <table cellspacing='0' cellpadding='0'>
      <tr>
        <!--<td>#</td>-->
        <td>{{__('extranet.booking-confirmation.guest')}}</td>
        <td>{{__('extranet.booking-confirmation.reservation_from')}}</td>
        <td>{{__('extranet.booking-confirmation.nights')}}</td>
        <td>{{__('extranet.booking-confirmation.occupancies')}}</td>
        <td>{{__('extranet.booking-confirmation.total')}}</td>
        <td class="noshow">{{__('extranet.booking-confirmation.arrival')}}</td>
      </tr>

      <tr v-for='reservation in reservations'>
        <!--<td>{{ reservation.id }}</td>-->
        <td>{{ reservation.guest_name }}</td>
        <td>{{ new Date(reservation.from).toVerboseDateString() }}</td>
        <td>{{ reservation.nights }}</td>
        <td>
          <p v-for="(count, name) in groupOccupancies(reservation.occupancies)">{{ count }} x {{ name }}</p>
        </td>
        <td>{{ toCurrency(reservation.total) }}</td>
        <td :class="{noshow: true, disabled: !!reservation.disabled}">
          <div class="show-selector">
            <button :class="{selected: reservation.noshow === false}" @click="markShow({reservationID: reservation.id, show: true})">{{__('extranet.booking-confirmation.show')}}</button>
            <button :class="{selected: reservation.noshow === true}" @click="markShow({reservationID: reservation.id, show: false})">{{__('extranet.booking-confirmation.no_show')}}</button>
          </div>
        </td>
      </tr>

      <tr style="height: 80px" v-if="Object.keys(reservations).length > 0 && reservationsMarked.length">
        <td colspan="7"><button @click="confirm" style="float: right;">{{__('extranet.booking-confirmation.save')}}</button></td>
      </tr>

      <tr style="height: 300px; color: #212121; opacity: 0.7; text-align: center" v-if="Object.keys(reservations).length === 0">
        <td colspan="7">{{__('extranet.booking-confirmation.no_records')}}</td>
      </tr>

    </table>

  </div>
</template>

<script>
  import DateFilter from '../DateFilter.vue';
  import FilterText from '../../../admin/components/Filters/FilterText.vue';
  import FilterSelect from '../../../admin/components/Filters/FilterSelect.vue';

  import { mapState, mapMutations, mapActions, mapGetters   } from 'vuex'

  export default {
    components:
      {DateFilter, FilterText, FilterSelect},
    computed: {
      ...mapState('ReservationConfirmation', ['reservations']),
      ...mapGetters('ReservationConfirmation', ['reservationsMarked']),
    },
    methods: {
      ...mapMutations('ReservationConfirmation', ['markShow']),
      ...mapActions('ReservationConfirmation', ['load', 'confirm']),
      groupOccupancies(occupancies) {
        return occupancies.reduce((r, occupancy) => {
          if (r[occupancy.room.name]) r[occupancy.room.name] ++;
          else r[occupancy.room.name] = 1;

          return r;
        }, {});
      }
    },
    created() {
      this.load();
    }
  }
</script>