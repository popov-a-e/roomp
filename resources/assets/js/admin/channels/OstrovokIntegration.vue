<template>
  <div class="ostrovok_integration" v-if="module_active">
    <h5>Интеграция с Ostrovok.ru</h5>
    <div class="ota">
      <div class="hotel">
        <p>ID отеля в Ostrovok.ru: {{ ostrovok_id || "Нет" }}</p>

        <button class="select_hotel" @click="toggleOstrovokHotels">Выбрать другой отель</button>
        <button class="delete_connection" @click="deleteOstrovokConnection">Отвязать отель</button>

        <div class="admin-overlay" v-if="ostrovok_hotels_visible" @click.prevent="toggleOstrovokHotels">
          <ostrovok-hotels @input="setOstrovokHotel"></ostrovok-hotels>
        </div>

        <button class="apply" v-if="ostrovokIDChanged" @click="saveHotel">Подтвердить изменения</button>
      </div>
      <div class="rooms" v-if="roomp_rooms && ostrovok_rooms">
        <div class="mappings">
          <label class="th-booking">Комната в Ostrovok</label><label class="th-roomp">Комната в Roomp</label>
          <label class="tr" v-for="room in ostrovok_rooms" >
            <span>{{room.name}}: {{room.ostrovok_id}}</span>
            <select  @input="e => setMappingRoom({ostrovok_id: room.ostrovok_id, roomp_id: e.target.value})">
              <option :value="null" :selected="room.room_id === null">Сопоставление не задано</option>
              <option v-for="roomp_room in roomp_rooms" :value="roomp_room.id"
                      :selected="room.room_id !== null && Number(roomp_room.id) === Number(room.room_id)">
                {{roomp_room.name}}: {{roomp_room['class'].ru}}, {{roomp_room.allotments.map(a => a.ru).join(',') }}
              </option>
            </select>
          </label>
        </div>

        <button @click="setRooms" :class="rates_upload_status">{{roomUploadStatus}}</button>
      </div>
    </div>
  </div>
</template>

<script>
  import OstrovokHotels from './OstrovokHotels.vue';
  import {mapState, mapMutations, mapActions, mapGetters} from 'vuex';
  import OstrovokIntegration from "../store/modules/OstrovokIntegration";

  export default {
    components: {OstrovokHotels},
    module: {OstrovokIntegration},
    data() {
      return {
        ostrovok_hotels_visible: false
      }
    },
    props: ['hotel-i-d'],
    methods: {
      initModule() {
        this.setHotelID(this.hotelID);
        this.load();
      },
      ...OstrovokIntegration.mapMethods(),
      toggleOstrovokHotels() {this.ostrovok_hotels_visible = !this.ostrovok_hotels_visible;},
      setOstrovokHotel(hotel) {
        this.toggleOstrovokHotels();
        this.setOstrovokID(hotel.id);
      }
    },
    computed: {
      ...OstrovokIntegration.mapProps()
    },
    created () {
      this.$watch('hotelID', e => e && this.$emit('module_updated', e));

      this.$emit('module_updated');
    }
  }
</script>