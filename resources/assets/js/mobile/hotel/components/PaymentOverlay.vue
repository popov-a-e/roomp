<template>
  <div class='payment-overlay' v-on:click='togglePaymentOverlay'>
    <div class='payment-overlay-inner' v-on:click.stop=''>
      <h2>{{ __('hotel.your_order') }}</h2>
      <table cellspacing='0' cellpadding='0'>
        <tr>
          <th>{{ __("hotel.room")}}</th>
          <th>{{ __("hotel.price")}}</th>
        </tr>
        <tr v-for='(room, i) in rooms'>
          <td>{{ classes.where("id",room.class).name }}, {{ allotments.where("id",room.allotment).name }}</td>
          <td v-html="nights + ' x ' + toCurrency(calcPrice(room))"></td>
        </tr>
        <tr v-if='promo_code_data'>
          <td>{{ __('hotel.discount') }}</td>
          <td v-html='promoString'></td>
        </tr>
        <tr>
          <td>{{ __("hotel.total_sum")}}</td>
          <td v-html="toCurrency(sum)"></td>
        </tr>
      </table>
      <div class='nav'>
        <button class='pay' v-on:click='pay'>{{ __("hotel.confirm") }}</button>
        <button class='back' v-on:click='togglePaymentOverlay'>{{ __("hotel.cancel") }}</button>
      </div>
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
      ...mapState('Cart', ['rooms']),
      ...mapState('BackendData',['classes', 'allotments', 'policy']),
      ...mapGetters('Cart', ['nights', 'sum', 'discountedSum', 'promoString']),
      ...mapState(['promo_code_data'])
    },
    methods: {
      ...mapActions(['pay']),
      ...mapMutations('Appearance', ['togglePaymentOverlay']),
      calcPrice(room) {
        const capacity = Object.keys(room.room.prices).length;
        const policy = this.policy;
        const regularAdultNumber = Math.min(capacity, room.adults);

        const extraAdultNumber = room.adults - regularAdultNumber;

        const priceAdults = room.room.prices[regularAdultNumber] + extraAdultNumber * policy.price_adults;
        const priceChildren = room.children * policy.price_children;
        const priceInfants = room.infants * policy.price_infants;

        return priceAdults + priceChildren + priceInfants;
      }
    }
  }
</script>