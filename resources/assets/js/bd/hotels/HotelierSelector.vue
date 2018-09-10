<template>
  <div class="organization-selector" @click.prevent.stop="">
    <input placeholder="Введите название" v-model="name" />

    <table v-if="hotels.length > 0" cellspacing="0" cellpadding="0">
      <tr>
        <th>
          Название
        </th>
        <th>
          Оригинальное название
        </th>
      </tr>
      <tr @click="selectHotelier(hotel.hotelier)" v-for="hotel in hotels">
        <td>
          {{ hotel.ru }}
        </td>
        <td>
          {{ hotel.regular_name }}
        </td>
      </tr>
    </table>
    <div v-else>
      Ничего не найдено
    </div>
  </div>
</template>

<script>
  import {mapActions, mapMutations, mapState, mapGetters} from 'vuex';

  export default {
    data() {
      return {}
    },
    methods: {
      ...mapActions('Hotel', ['selectHotelier']),
      ...mapActions('Hotel/HotelierSelector', ['load'])
    },
    computed: {
      ...mapState('Hotel/HotelierSelector', ['hotels']),
      name: {
        get() {
          return this.$store.state.Hotel.HotelierSelector.name;
        },
        set(value) {
          this.$store.commit('Hotel/HotelierSelector/setName', value);
          if (value.length > 0) {
            this.load();
          } else {
            this.$store.commit('Hotel/HotelierSelector/setHotels', []);
          }
        }
      }
    }
  }
</script>