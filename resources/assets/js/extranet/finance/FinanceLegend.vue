<template>
  <div class="finance-legend">
    <p class="description">
      {{__('extranet.finance.help_info_text')}}
    </p>
    <p class="discount" v-if="discount !== false">
      {{__('extranet.finance.discount_rate', {discount})}}
    </p>
<!--
    <div class="payment-status-legend">
      <h3>{{__('extranet.finance.payment_status')}}</h3>

      <span>{{__('extranet.finance.invoice_wait')}}</span>
      <payment-status-icon style="padding-left: 5px;" :type="'unpaid'"></payment-status-icon>

      <span>{{__('extranet.finance.invoice_done')}}</span>
      <payment-status-icon style="padding-left: 5px;" :type="'paid'"></payment-status-icon>

      <span>{{__('extranet.finance.invoice_roomp')}}</span>
      <payment-status-icon style="padding-left: 5px;" ></payment-status-icon>
    </div>-->
  </div>
</template>

<script>
  import PaymentStatusIcon from './PaymentStatusIcon.vue';
  import {mapState} from 'vuex';

  export default {
    components: {PaymentStatusIcon},
    computed: mapState('Finance', ['discount'])
  }
</script>