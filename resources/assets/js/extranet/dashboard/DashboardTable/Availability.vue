<template>
  <div class="availability" v-if='this.availability'>
    <div v-for="day in dates" class="availability-cell">
      {{available(day)}}
    </div>
  </div>
</template>

<script>
  import {mapGetters, mapState} from 'vuex';

  export default {
    props: ['roomID'],
    computed: {
      ...mapGetters('DashboardTable', ['dates']),
      ...mapState('DashboardTable', ['availability']),
    },
    methods: {
      available(day) {
        const roomData = this.availability[this.roomID];
        return roomData && roomData[day];
      },
    },
  }
</script>