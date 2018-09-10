<template>
  <div class='hotels-list'>
    <div class="hotels">
      <hotel-card v-for="hotel in hotels"
                  :hotel="hotel"></hotel-card>

      <button class="hotel add-hotel" v-if="stage === 'initial'" @click="showModal">+</button>
    </div>
  </div>
</template>

<script>
  import {mapState, mapMutations, mapGetters} from 'vuex';
  import HotelCard from './Hotel/HotelCard.vue'
  import NewHotel from './Hotel/NewHotel.vue'


  export default {
    components: {
      HotelCard, NewHotel
    },
    props: ['stage'],
    computed: {
      ...mapState('Meta', ['cities']),
      ...mapGetters('Onboarding', ['initial', 'signing', 'active', 'ready', 'deleted', 'thinking']),
      hotels() {
        return this[this.stage];
      }
    },
    methods: {
      ...mapMutations('Onboarding', ['showModal']),
    },
    mounted() {
    }
  }
</script>