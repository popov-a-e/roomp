<template>
  <div class='payment' v-if='paymentStatus !== true'>
    {{ __('reservation.payment_status') }}: {{ paymentStatus }}
  </div>
</template>

<script>
  import { mapState, mapActions, mapMutations, mapGetters } from 'vuex';

  export default {
    data () {
      return {}
    },
    computed: mapGetters(['paymentStatus']),
    created () {
      this.$store.dispatch('checkPayment');
    }
  }
</script>