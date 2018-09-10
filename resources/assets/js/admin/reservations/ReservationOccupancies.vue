<template>
  <div class='card reservation-occupancies' v-if='reservation && module_active' @click.stop=''>
    <h2>Сводка бронирования</h2>
    
    <div class='card-content'>
      <template v-if='!reservation_edited'>
        <div class='reservation-info'>
          <a :href="`#/reservations/${reservation.code}`">Бронирование {{ reservation.code }}</a>
          <p>{{ reservation.from }} - {{ reservation.tillFix }}</p>
          <p>Гость: {{ reservation.guest_name }}</p>
          <button class='edit' @click='editReservation'>Изменить</button>
        </div>
      </template>
      <template v-else>
        <div class='reservation-info'>
          <a :href="`#/reservations/${reservation.code}`">Бронирование {{ reservation.code }}</a>
          <date-filter :from_prop="from" :till_prop="till" @input="setDates"></date-filter>
          <p>Гость: <input :value='reservation.guest_name' @input='e => setGuestName(e.target.value)' /></p>
          <button class='save' @click='saveReservation'>Сохранить</button>
          <button class='cancel'  @click='cancelEditingReservation'>Отмена</button>
        </div>
      </template>
      <table cellpadding='0' cellspacing='0' class='bare'>
        <tr class='th'>
          <th class='room'>Комната</th>
          <th class='adults'>Взрослых</th>
          <th class='children'>Детей</th>
          <th class='infants'>Младенцев</th>
          <th class='price'>Цена за ночь</th>
          <th class='rates'>Roomp Rates</th>
        </tr>
        <tr class='occupancy' v-for='occupancy in reservation.occupancies'>
          <td class='room'>
            <a :href='`#/hotels/${reservation.hotel_id}/${occupancy.room_id}`'>{{occupancy.room.class.ru}}, {{allotment(occupancy.allotment_id)}}</a>
          </td>
          <template v-if='edited !== occupancy.id'>
            <td class='adults'>
              {{ occupancy.adults }}
              <span v-if='extraAdults(occupancy)'>(+ {{ reservation.hotel.policy.price_adults * extraAdults(occupancy)}} р.)</span>
            </td>
            <td class='children' :title='reservation.hotel.policy.price_children'>
              {{ occupancy.children }}
              <span v-if='occupancy.children > 0'>(+ {{ reservation.hotel.policy.price_children * occupancy.children}} р.)</span>
            </td>
            <td class='infants' :title='reservation.hotel.policy.price_infants' >
              {{ occupancy.infants }}
              <span v-if='occupancy.infants > 0'>(+ {{ reservation.hotel.policy.price_infants * occupancy.infants}} р.)</span>
            </td>
            <td class='price'>
              <table class='bare'>
                <tr v-for='(price, i) in occupancy.prices'>
                  <td>{{dates[i] ? dates[i].toISODateString() : null}}</td>
                  <td>{{ price }}</td>
                </tr>
              </table>
            </td>
            <td class='rates'>
              <table class='bare'>
                <tr v-for='(rate, i) in occupancy.rates'>
                  <td>{{dates[i] ? dates[i].toISODateString() : null}}</td>
                  <td v-if='reservation.hotel.dynamic_roomp_rate === false' :title='getRate(getSeason(dates[i]), occupancy)'>
                    {{ getSeason(dates[i]).name }}
                  </td>
                  <td>{{ rate }}</td>
                </tr>
              </table>
            </td>
            <td class='buttons'>
              <button @click='e => toggleEdit(occupancy)'>Изменить</button>
            </td>
          </template>
          <template v-else>
            <td class='adults'>
              <input v-model='occupancy_edited.adults' @input='e => setOccupancyAdults(e.target.value)'/>
            </td>
            <td class='children' :title='reservation.hotel.policy.price_children' >
              <input v-model='occupancy_edited.children'  @input='e => setOccupancyChildren(e.target.value)' />
            </td>
            <td class='infants' :title='reservation.hotel.policy.price_infants'>
              <input v-model='occupancy_edited.infants' @input='e => setOccupancyInfants(e.target.value)' />
            </td>
            <td class='price'>
              <table class='bare'>
                <tr v-for='(price, i) in occupancy_edited.prices'>
                  <td>
                    {{dates[i] ? dates[i].toISODateString() : null}}
                  </td>
                  <td>
                    <input :value='price' @input='e => setPrice({index: i, value: e.target.value})' />
                  </td>
                </tr>
              </table>
            </td>
            <td class='rates'>
              <table class='bare'>
                <tr v-for='(rate, i) in occupancy_edited.rates'>
                  <td>
                    {{dates[i] ? dates[i].toISODateString() : null}}
                  </td>
                  <td v-if='reservation.hotel.dynamic_roomp_rate === false'  :title='getRate(getSeason(dates[i]), occupancy)'>
                    {{ getSeason(dates[i]).name }}
                  </td>
                  <td>
                    <input :value='rate' @input='e => setRate({index: i, value: e.target.value})' />
                  </td>
                </tr>
              </table>
            </td>
            <td class='buttons'>
              <button @click='save'>Сохранить</button>
              <button @click='toggleEdit'>Отмена</button>
            </td>
          </template>
        </tr>
        <tr v-if='reservation.promo_code'>
          <td colspan='4'>Скидка по промокоду</td>
          <td>- {{ reservation.discount }}</td>
          <td></td>
        </tr>
        <tr>
          <td>Итого</td>
          <td>{{ priceAdultsExtra }}</td>
          <td>{{ priceChildren }}</td>
          <td>{{ priceInfants }}</td>
          <td>{{ reservation.total }}</td>
          <td>{{ reservation.rate - (priceAdultsExtra + priceChildren + priceInfants) }} + {{ priceAdultsExtra + priceChildren + priceInfants}} = {{ reservation.rate}}</td>
        </tr>
      </table>
    </div>
  </div>
</template>

<script>
  import DateFilter from './../components/DateFilter.vue';
  import {mapState} from 'vuex';
  import ReservationOccupancies from '../store/modules/ReservationOccupancies';
  import rateParser from '../rates/rateParser';

  export default {
    components: {DateFilter},
    data() {
      return {
        module_id: this.reservationID
      }
    },
    module: {ReservationOccupancies},
    props: ['reservation-i-d'],
    computed: {
      ...ReservationOccupancies.mapProps(),
      ...mapState('Meta', ['allotments']),
      priceAdultsExtra() {
        return this.reservation.occupancies.reduce((p,c) => p + this.extraAdults(c), 0) * this.reservation.hotel.policy.price_adults;
      },
      priceInfants() {
        return this.reservation.occupancies.reduce((p,c) => p + c.infants, 0) * this.reservation.hotel.policy.price_adults;
      },
      priceChildren() {
        return this.reservation.occupancies.reduce((p,c) => p + c.children, 0) * this.reservation.hotel.policy.price_adults;
      },
      dates() {
        let dates = [];
        const from = new Date(this.reservation.from);
        const till = new Date(this.reservation.till);

        for(let i = new Date(this.reservation.from); +i <= +till; i.setDate(i.getDate() + 1)) {
          dates.push(new Date(+i));
        }

        return dates;
      }
    },
    methods: {
      initModule() {
        this.setID(this.reservationID);

        if (this.allotments.length === 0) {
          this.$store.dispatch('Meta/loadAll').then(this.load);
        } else this.load();
      },
      allotment(id) {
        return this.allotments.where('id', id).ru;
      },
      getRate (season, occupancy) {
        if (!season) return '';
        
        const policy = this.reservation.hotel.policy;
        const capacity = occupancy.room.max_guest_number;
        
        const adults = Math.min(occupancy.adults, capacity);
        
        return season.rates.filter(rate => rate.room_class_id === occupancy.room.room_class_id && rate.guest_number === adults)[0].rate;
      },
      getSeason(date) {
        let seasons = Array.from(this.reservation.hotel.seasons);

        const index = seasons.findIndex(season => season.default);
        const defSeason = seasons.splice(index, 1)[0];
        seasons.unshift(defSeason);
        
        return seasons.reduce((p,c) => {
          if (rateParser(c.rule)(date)) return c;
          return p;
        });
      },
      extraAdults(occupancy) {
        return occupancy.adults - Math.min(occupancy.adults, occupancy.room.max_guest_number);
      },
      ...ReservationOccupancies.mapMethods(),
    },
    created() {
      this.$on('module_activated', () => {
        this.$watch('reservation', () => {
          this.$emit('change');
        });
      });
      
      this.$watch('module_id', e => this.$emit('module_updated', e));
      this.$emit('module_updated');
    }
  }
</script>