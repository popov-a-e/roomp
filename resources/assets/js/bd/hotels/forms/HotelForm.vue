<template>
  <form class="hotel" @submit.prevent="save">
    <h3>Отель</h3>


    <v-input-cancelable :highlighted="highlighted.hotel.ru"
                        :placeholder="'Название отеля Roomp'" :value="ru"
                        @input="setName"></v-input-cancelable>

    <v-select
      :highlighted="highlighted.hotel.city_id"
      :placeholder="'Город'"
      :select_placeholder="'Выберите город'"
      :value="city_id"
      :options="cities"

      @input="setCity"
    ></v-select>

    <v-select
      :highlighted="highlighted.hotel.channel_id"
      :placeholder="'Channel Manager'"
      :value="channel_id"
      :options="channels"

      @input="setChannel"
    ></v-select>

    <v-input-cancelable :highlighted="highlighted.hotel.regular_name"
                        :placeholder="'Название отеля'" :value="regular_name"
                        @input="setRegularName"></v-input-cancelable>

    <v-input-cancelable :highlighted="highlighted.hotel.address_ru"
                        :placeholder="'Адрес'" :value="address_ru"
                        @input="setAddress"></v-input-cancelable>

    <v-input-cancelable :highlighted="highlighted.hotel.reception_email"
                        :placeholder="'E-mail для бронирований'" :type="'email'" :value="reception_email"
                        @input="setReceptionEmail"></v-input-cancelable>

    <v-input-cancelable :placeholder="'Телефон рецепции'" :value="reception_phone"
                        @input="setReceptionPhone"></v-input-cancelable>

    <v-input-cancelable :placeholder="'Номерной фонд'" :value="room_number"
                        @input="setRoomNumber"></v-input-cancelable>

    <v-input-cancelable :placeholder="'Ставка дисконта, %'" :value="dynamic_roomp_rate_discount"
                        @input="setDiscount"></v-input-cancelable>
  </form>
</template>

<script>
  import VInputCancelable from '../../components/VInputCancelable.vue';
  import VSelect from '../../components/VSelect.vue';
  import {mapActions, mapMutations, mapState, mapGetters} from 'vuex';

  export default {
    components: {VInputCancelable, VSelect},
    data() {
      return {}
    },
    methods: {
      submit() {
        this.$emit('submit');
      },
      ...mapMutations('Hotel/HotelForm', [
        'setName',
        'setRegularName',
        'setCity',
        'setChannel',
        'setReceptionEmail',
        'setReceptionPhone',
        'setRoomNumber',
        'setAddress',
        'setDiscount',
        'init'
      ]),
      ...mapActions('Hotel/HotelForm', ['save']),
      ...mapActions('Hotel', ['deferSave'])
    },
    computed: {
      ...mapState('Hotel/HotelForm', [
        'ru',
        'city_id',
        'channel_id',
        'regular_name',
        'address_ru',
        'reception_email',
        'reception_phone',
        'room_number',
        'dynamic_roomp_rate_discount'
      ]),
      ...mapState('Hotel', ['hotel']),
      ...mapState('Meta', ['cities', 'channels']),
      ...mapGetters('Hotel/HotelForm', ['state']),
      ...mapGetters('Hotel', ['highlighted'])
    },
    created() {
      this.init(this.hotel);

      this.$watch('state', () => this.deferSave(this.save).then(delay => delay()));
    }
  }
</script>