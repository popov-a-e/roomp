<template>
  <div class='guest_selector'>
    <div @click.prevent.stop='toggle' class='guest_selector_data'>
      <span class='guests'>{{ pluralize('common.guests', adult_number + children_number) }}</span>
      <span class='rooms'>{{ pluralize('common.rooms', room_number) }}</span>
    </div>
    <guest-dialog v-if='guest_selector_visible'></guest-dialog>
  </div>
</template>

<script>
  import GuestDialog from './GuestsDialog.vue';
  import { mapState, mapActions, mapMutations, mapGetters } from 'vuex';
  import module from './store/GuestSelector';
  import Bus from '../../Bus';
  
  export default {
    components: {GuestDialog},
    data () {
      return {
      }
    },
    computed: {
      ...module.mapProps('GuestSelector')
    },
    methods: {
      ...module.mapMethods('GuestSelector'),
      toggle () {
        this.toggleGuestSelector();
        Bus.$emit('click', this._uid);
      }
    },
    created () {
      Bus.$on('click', _uid => {
        if (this._uid !== _uid) this.hideGuestSelector();
      })
    }
  }
</script>