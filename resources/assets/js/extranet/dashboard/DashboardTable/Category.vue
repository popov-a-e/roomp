<template>
  <div class="category">

    <div class="category-header">
      <div>{{room.id}}</div>
      <div>{{room.name}}</div>
    </div>

    <occupancy
      v-for="rate in rates"
      :rateID="rate.id"
      :roomID="roomID"
    ></occupancy>

  </div>
</template>

<script>
  import {mapState} from 'vuex';
  import Occupancy from './Occupancy';

  export default {
    components: {Occupancy},
    props: ['roomCategory', 'roomID'],
    computed: {
      ...mapState('DashboardTable', ['rooms', 'rates']),
      room () {
        return this.rooms[this.roomID];
      },
    },

  }
</script>