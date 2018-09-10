<template>
  <div class="hotel-offer status-selector" v-if="hotel">
    <button @click="apply">Готовы</button>
    <button @click="postpone">Думают</button>
    <button @click="decline">Отказ</button>
  </div>
</template>

<script>
  import HotelForm from '../forms/HotelForm.vue';
  import ContactFace from '../forms/ContactFace.vue';
  import Organization from '../forms/Organization';
  import Rooms from '../Rooms/Rooms.vue';
  import Hotelier from './Hotelier.vue';
  import ProgressBar from '../../components/ProgressBar.vue';

  import {mapState, mapGetters, mapMutations, mapActions} from 'vuex';

  export default {
    components: {Organization, ProgressBar, Hotelier, HotelForm, ContactFace, Rooms},
    methods: {
      ...mapActions('Hotel', ['updateStatus']),
      apply() {
        this.updateStatus('signing').then(() => {
          this.$router.push(`/onboarding/${this.hotel.id}/edit`)
        });
      },
      decline() {
        this.updateStatus('deleted').then(() => {
          this.$router.push(`/onboarding/initial`)
        });
      },
      postpone() {
        this.updateStatus('thinking').then(() => {
          this.$router.push(`/onboarding/initial`);
        });
      }
    },
    computed: {
      ...mapState('Meta', ['allotments', 'room_classes', 'cities']),
      ...mapState('Hotel', ['hotel', 'is_loading']),
    },
  }
</script>