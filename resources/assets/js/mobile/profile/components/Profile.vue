<template>
  <div class='profile'>
    <profile-nav></profile-nav>
    <div class='tabs'>
      <user-info></user-info>
      <reservations></reservations>
    </div>
  </div>
</template>

<script>
  import ProfileNav from './ProfileNav.vue'
  import UserInfo from './UserInfo.vue';
  import Reservations from './Reservations.vue';
  import { mapState, mapActions, mapMutations, mapGetters } from 'vuex';

  export default {
    components: {
      ProfileNav, UserInfo, Reservations
    },
    data () {
      return {}
      
    },
    computed: {},
    methods: {}
  }
</script>