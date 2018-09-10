<template>
  <div class='bill'>
    <div class='row'>
      <div class='info'> {{infoString }}</div>
      <div class='cost' v-html="toCurrency(sum)"></div>
    </div>
    
    <div class='row' v-if='promo_code_data'>
      <div class='info'>{{ __('hotel.discount') }}</div>
      <div class='cost' v-html='promoString'></div>
    </div>
    
    <div class='row'>
      <div class='info'>{{ __('hotel.total_sum') }}</div>
      <div class='cost' v-html="toCurrency(discountedSum)"></div>
    </div>
    
    <div class='breakfast'>
      {{ __(hasBreakfast ? 'hotel.breakfast' : 'hotel.no_breakfast') }}
    </div>
  </div>
</template>

<script>
  import {mapState, mapGetters} from 'vuex';

  export default {
    computed: {
      ...mapGetters('Cart',['sum', 'infoString', 'discountedSum', 'promoString']),
      ...mapState(['hasBreakfast', 'promo_code_data'])
    }
  }
</script>