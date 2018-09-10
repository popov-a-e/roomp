<template>
  <form class="hotelier">
    <h3>Аккаунт</h3>
    <button @click="showHotelierSelector">Выбрать из существующих</button>

    <label class="v-input">
      <span>E-mail</span>
      <p v-if="email"> {{ email }} </p>
      <p v-else>Не зарегистрирован</p>
    </label>
    <label class="v-input">
      <span>Отели</span>
      <p v-if="hotels && hotels.length > 0">{{ hotelsString }}</p>
      <p v-else>Нет</p>
    </label>
  </form>
</template>

<script>
  import {mapActions, mapMutations, mapState, mapGetters} from 'vuex';

  export default {
    data() {
      return {}
    },
    methods: {
      ...mapMutations('Hotel', ['showHotelierSelector']),
      ...mapMutations('Hotel/Hotelier', ['setHotelier'])
    },
    computed: {
      ...mapState('Hotel', ['hotel']),
      ...mapState('Hotel/Hotelier', ['email', 'hotels']),
      ...mapGetters('Hotel/Hotelier', ['hotelsString'])
    },
    created() {
      this.setHotelier(this.hotel.hotelier);
    }
  }
</script>