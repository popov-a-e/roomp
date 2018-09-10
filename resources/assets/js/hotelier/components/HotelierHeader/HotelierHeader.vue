<template>
  <header>
    <img src="/img/logo.png">

    <div>
      <div>Календарь</div>
      <div>Бронирования</div>
      <div>Финансы</div>
      <div>Помощь</div>
      <div class="dropdown" @click="dropdown = !dropdown">{{hotels[0].name}} Отель

        <div v-if="dropdown">
          <div v-for="hotel in hotels">{{hotel.name}}</div>
          <div>Настройки</div>
          <div>Объект Размещения</div>
          <div>Организация</div>
          <div href='http://extranet.simon.dev.roomp.co/logout'>Выйти</div>
        </div>
      </div>

      <i class="fas fa-sign-out-alt"></i>
    </div>

   <!-- <a href='/logout' class='user'>{{ user.name }} : Выйти</a>
    <cselect class='lang' :options='hotels' :selected='hotel.id' :name='`Ваши отели`' @input='setHotel'></cselect> -->
  </header>
</template>

<script>
  import Cselect from '../../../components/Cselect.vue';
  import { mapState, mapActions, mapMutations, mapGetters } from 'vuex';

  export default {
    data () {
      return {
        dropdown: false,
      }
    },
    components: {Cselect},
    props: ['user', 'hotels', 'hotel'],
    computed: {
    },
    methods: {
      ...mapMutations('Header', ['initialize', 'setHotels']),
      ...mapActions('Header', ['setHotel']),
    },
    created () {
      this.initialize({
        hotels: this.hotels,
        hotel: this.hotel
      });
    }
  }
</script>