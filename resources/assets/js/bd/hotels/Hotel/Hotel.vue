<template>
  <div class="hotel">
    <router-view>
    </router-view>
    <div class="spinner" v-if="!hotel">
      <i class="fa fa-circle-o-notch fa-spin"></i>
    </div>
  </div>
</template>

<script>
  import {mapActions, mapMutations, mapState, mapGetters} from 'vuex';
  import HotelOffer from './HotelOffer.vue';
  import OfferStatus from './OfferStatus.vue';

  export default {
    components: {HotelOffer, OfferStatus},
    props: ['hotel_id'],
    methods: {
      ...mapMutations('Hotel', ['flush']),
      ...mapActions('Hotel', ['load'])
    },
    computed: {
      ...mapState('Hotel', ['hotel']),
      ...mapGetters('Hotel', ['stage']),
    },
    created() {
      this.load(this.hotel_id);
    },
    beforeDestroy() {
      this.flush();
    }
  }
</script>