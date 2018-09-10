<template>
  <div class='allotments'>
    <div class='guest_selector' @click='toggleGuestOverlay'>
      {{ pluralize('common.guests', adult_number + children_number) }}, {{ pluralize('common.rooms', room_number)}}
    </div>
    
    <h2>{{ __("search.type") }}</h2>
    <div>
      <cbutton
        v-for="allotment in allotmentsArray"
        :name="allotment.name"
        :id="allotment.id"
        :value="allotments.indexOf(allotment.id) >= 0"
        v-on:input="toggleAllotment"
      >
      </cbutton>
    </div>
    <hr />
  </div>
</template>

<script>
  import Cbutton from '../../../components/Cbutton.vue';
  import Cselect from './../../components/Cselect';
  import { mapState, mapActions, mapMutations, mapGetters } from 'vuex';

  export default {
    components: {Cbutton, Cselect},
    data () {
      return {}
    },
    computed: {
      ...mapState('BackendData', {
        allotmentsArray: 'allotments'
      }),
      ...mapState('Filters', ['allotments']),
      ...mapState('GuestSelector', ['adult_number', 'children_number', 'room_number']),
      ...mapState('BackendData', ['cities', 'roomNumbers', 'guestNumbers']),
      ...mapGetters('BackendData', ['roomNumbersBounded']),
    },
    methods: {
      ...mapMutations('Filters',['toggleAllotment', 'setGuestNumber', 'setRoomNumber']),
      ...mapMutations('Appearance', ['toggleGuestOverlay'])
    }
  }
</script>