<template>
  <div class="rooms">
    <room-list></room-list>
    <button @click="selectRoom('new')">Добавить номер</button>
  </div>
</template>

<script>
  import RoomList from './RoomList.vue';
  import {mapActions, mapMutations, mapState, mapGetters} from 'vuex';

  export default {
    components: {RoomList},
    methods: mapMutations('Hotel/Rooms', ['selectRoom']),
    computed: mapState('Hotel/Rooms', ['selected']),
  }
</script>