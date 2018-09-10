<template>
  <div class='overlay guest_overlay' @click='toggleGuestOverlay'>
    <guest-dialog class='content'></guest-dialog>
  </div>
</template>

<script>
  import GuestDialog from '../../../components/GuestSelector/mobile/GuestsDialog.vue';
  import {mapState, mapActions, mapMutations, mapGetters} from 'vuex';

  export default {
    components: {GuestDialog},
    data() {
      return {}
    },
    computed: {},
    methods: {
      ...mapMutations('Appearance', ['toggleGuestOverlay'])
    }
  }
</script>