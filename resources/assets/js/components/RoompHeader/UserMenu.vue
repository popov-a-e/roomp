<template>
  <div class='user-menu'>
    <a href="/profile#/">{{ __('header.profile')}}</a>
    <a href="/profile#/reservations">{{__('header.my_bookings')}}</a>
    <a href="/auth/logout">{{__('header.logout')}}</a>
  </div>
</template>

<script>
  import { mapState, mapActions, mapMutations, mapGetters } from 'vuex';

  export default {
    data () {
      return {}
    },
    computed: {},
    methods: {}
  }
</script>