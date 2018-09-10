<template>
  <div class='book'>
    <return-back></return-back>
    <user-info></user-info>
    <promo></promo>
    <bill></bill>
    <div class='buttons'>
      <button v-if='paymentOffline' class='offline' v-bind:class='{ alone: !paymentOnline }' @click='book'>
        {{ __('hotel.pay_at_hotel') }}
      </button>
      <button v-if='paymentOnline' class='online' :class='{alone: !paymentOffline}' v-on:click='togglePaymentOverlay'>
        {{ __('hotel.pay_online') }}
      </button>
    </div>
  </div>
</template>

<script>
  import ReturnBack from './ReturnBack.vue';
  import UserInfo from './UserInfo.vue';
  import Promo from './Promo.vue';
  import Bill from './../Bill.vue';
  
  import { mapActions, mapMutations, mapState } from 'vuex';
  
  export default {
    components: {
      ReturnBack, UserInfo, Promo, Bill
    },
    methods: {
      ...mapMutations('Appearance',['togglePaymentOverlay']),
      ...mapActions(['book'])
    },
    computed: mapState(['paymentOnline', 'paymentOffline'])
  }
</script>