<template>
  <div class='profile'>
    <div class='tabs'>
      <user-info></user-info>
      <reservations></reservations>
    </div>
  </div>
</template>

<script>
  import UserInfo from './UserInfo.vue';
  import Reservations from './Reservations.vue';
  import { mapState, mapActions, mapMutations, mapGetters } from 'vuex';

  export default {
    components: {
      UserInfo, Reservations
    },
    data () {
      return {}
      
    },
    computed: {},
    methods: {}
  }
</script>