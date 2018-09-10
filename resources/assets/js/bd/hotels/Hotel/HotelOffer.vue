<template>
  <div class="hotel-offer" v-if="hotel">
    <nav>
      <progress-bar @click="highlightRequired" :progress="completedBy"></progress-bar>
      <button :disabled="completedBy < 100" class="apply" @click="generateOffer">
        <template v-if="is_loading">
          <i class="fa fa-spin fa-spinner"></i>
        </template>
        <template v-if="stage === 'initial'">
          Создать оферту
        </template>
        <template v-else>
          Обновить оферту
        </template>
      </button>
    </nav>

    <hotel-form></hotel-form>

    <hotelier></hotelier>

    <organization></organization>

    <contact-face></contact-face>

    <rooms></rooms>
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
      ...mapActions('Hotel', ['load', 'generateOffer', 'highlightRequired']),
    },
    computed: {
      ...mapState('Meta', ['allotments', 'room_classes', 'cities']),
      ...mapState('Hotel', ['hotel', 'is_loading']),
      ...mapGetters('Hotel', ['completedBy', 'stage']),
    },
  }
</script>