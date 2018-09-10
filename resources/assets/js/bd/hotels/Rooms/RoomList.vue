<template>
  <div class="room-list">
    <h3>Номера в отеле</h3>
    <table cellpadding="0" cellspacing="0">
      <tr>
        <th>ID</th>
        <th>Название</th>
        <th>Класс</th>
        <th>Размещение</th>
        <th>Кол-во</th>
      </tr>
      <tr v-for="room in rooms" @click="selectRoom(room)">
        <td>{{room.id}}</td>
        <td>{{room.name}}</td>
        <td>{{roomClass(room)}}</td>
        <td>{{roomAllotments(room)}}</td>
        <td>{{room.number}}</td>
      </tr>
    </table>
  </div>
</template>

<script>
  import {mapActions, mapMutations, mapState, mapGetters} from 'vuex';

  export default {
    data() {
      return {}
    },
    methods: {
      roomAllotments(room) {
        return (
          room.allotments &&
          room.allotments.map(index => this.allotments.where('id', index).ru).join(' / ')
        );
      },
      roomClass(room) {
        return this.room_classes.where('id', room.room_class_id).ru;
      },
      ...mapMutations('Hotel/Rooms', ['init', 'selectRoom']),
    },
    computed: {
      ...mapState('Hotel/Rooms', ['rooms']),
      ...mapState('Hotel', ['hotel']),
      ...mapState('Meta', ['room_classes', 'allotments'])
    },
    created() {
      this.init(this.hotel.rooms);
    }
  }
</script>