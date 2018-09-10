<template>
  <div class='guest_selector' @click.prevent.stop=''>
    <div @click='toggleGuestsSelector' class='guest_selector_data'>
      <span class='guests'>{{ adultsNumbers[adults_number + children_number - 1].name }}</span>
      <span class='rooms'>{{ roomNumbers[roomNumber - 1].name }}</span>
    </div>
    <guest-dialog v-if='guest_selector_visible'></guest-dialog>
  </div>s
</template>

<script>
  import GuestDialog from './GuestsDialog.vue';
  import { mapState, mapActions, mapMutations, mapGetters } from 'vuex';

  export default {
    components: {GuestDialog},
    data () {
      return {
      }
    },
    computed: {
    },
    methods: {
    }
  }
</script>