<template>
  <div class="room" @click.stop="">
    <div class='basic-info'>
      <v-input @input="setName" :value="name" :placeholder="'Название'"></v-input>
      <v-input @input="setNumber" :value="number" :placeholder="'Количество'"></v-input>
      <v-input @input="setMaxGuestNumber" :value="max_guest_number" :placeholder="'Вместимость'"></v-input>
      <v-input @input="setSize" :value="size" :placeholder="'Размер (м2)'"></v-input>
    </div>
    <div class="select">
      <h3>Класс номера</h3>
      <div class='btn-container'>
        <c-button v-on:input='setRoomClass'
                  v-for='cl in room_classes'
                  :name='cl.name'
                  :id='cl.id'
                  :value='room_class_id === cl.id'></c-button>
      </div>
    </div>

    <div v-if='allotments' class="select">
      <h3>Размещение</h3>
      <div class='btn-container'>
        <c-button v-for='al in room_allotments'
                  v-on:input='setAllotment'
                  :name='al.name'
                  :id='al.id'
                  :value='allotments.indexOf(al.id) >= 0'></c-button>
      </div>
    </div>

    <div class="controls">
      <button @click='save' class="apply">Save</button>
      <button @click='del' class='delete' v-if="id">Delete</button>
    </div>
  </div>
</template>

<script>
  import CButton from '../../../components/Cbutton.vue';
  import VInput from '../../components/VInput.vue';
  import {mapState, mapActions, mapMutations, mapGetters} from 'vuex';

  export default {
    components: {
      CButton, VInput
    },
    computed: {
      ...mapState('Meta', ['room_classes', 'room_amenities']),
      ...mapState('Meta', {room_allotments: 'allotments'}),
      ...mapState('Room', ['id', 'name', 'size', 'max_guest_number', 'number', 'allotments', 'room_class_id']),
      ...mapState('Hotel/Rooms', {room: 'selected'})
    },
    methods: {
      ...mapMutations('Room', ['setRoom', 'setName', 'setSize', 'setMaxGuestNumber', 'setNumber', 'setAllotment', 'setRoomClass']),
      ...mapActions('Room', ['save', 'del'])
    },
    created () {
      console.log(this.room);
      if (!this.room) return;
      if (this.room === 'new') this.setRoom();
      else this.setRoom(this.room);
    },
  }
</script>