<template>
  <div class='promo'>
    <template v-if='!promo_code_data'>
      <input v-model='promoCode' :placeholder="__('hotel.promo_code')" />
      <button class="apply" @click='checkPromoCode'>{{ __('hotel.use_promo_code') }}</button>
      <p class='error' v-if='promo_code_error'>{{ promo_code_error }}</p>
    </template>
    <template v-else>
      <div class='success'>
        <p>{{ __('hotel.promo_code_activated', {promo_code: promoCode}) }}</p>
      </div>
      <button class='cancel' @click='cancelPromoCode'>{{ __('hotel.cancel') }}</button>
    </template>
  </div>
</template>

<script>
  import {mapState, mapActions, mapMutations, mapGetters} from 'vuex';

  export default {
    methods: {
      ...mapActions(['checkPromoCode']),
      ...mapMutations(['setPromoCodeData', 'cancelPromoCode']),
    },
    computed: {
      ...mapState(['promo_code_data', 'promo_code_error']),
      promoCode: {
        get() {
          return this.$store.state.promo_code;
        },
        set(value) {
          this.$store.commit('setPromoCode', value);
        }
      }
    }
  }
</script>