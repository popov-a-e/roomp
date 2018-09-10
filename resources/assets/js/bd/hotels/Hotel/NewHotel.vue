<template>
  <div class="new-hotel" @click.stop="">
    <h3>Добавить новый отель</h3>

    <v-input-cancelable :placeholder="'Название отеля Roomp'" :value="ru"
                        @input="setName"></v-input-cancelable>

    <v-select
      :placeholder="'Город'"
      :select_placeholder="'Выберите город'"
      :value="city_id"
      :options="cities"

      @input="setCity"
    ></v-select>

    <v-select
      :placeholder="'Channel Manager'"
      :value="channel_id"
      :options="channels"

      @input="setChannel"
    ></v-select>

    <v-input-cancelable :placeholder="'Название отеля'" :value="regular_name"
                        @input="setRegularName"></v-input-cancelable>

    <v-input-cancelable :placeholder="'Адрес'" :value="address_ru"
                        @input="setAddress"></v-input-cancelable>

    <button @click="save">Сохранить</button>
  </div>

</template>

<script>
  import VInputCancelable from '../../components/VInputCancelable.vue';
  import VSelect from '../../components/VSelect.vue';
  import {mapActions, mapMutations, mapState, mapGetters} from 'vuex';

  export default {
    components: {VInputCancelable, VSelect},
    computed: {
      ...mapState('Hotel/NewHotel', ['ru', 'regular_name', 'city_id', 'address_ru', 'channel_id']),
      ...mapState('Meta', ['cities', 'channels'])
    },
    methods: {
      ...mapMutations('Hotel/NewHotel', ['setName', 'setRegularName', 'setCity', 'setAddress', 'setChannel', 'clear']),
      ...mapActions('Hotel/NewHotel', ['save'])
    },
    created() {
      this.clear();
    }
  }
</script>