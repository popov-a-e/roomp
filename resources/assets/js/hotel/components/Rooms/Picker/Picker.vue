<template>
  <div class='picker'>
    <template v-if='loading === true'>
      <div class='loading'>{{__('common.loading')}}</div>
    </template>
    <template v-else-if='loading==="error"'>
      <div class='loading_error'>{{__('common.loading_error')}}</div>
    </template>
    <template v-else>
      <div class='empty' v-if='free.length === 0 && rooms.length === 0'>
        <span>{{ __('hotel.no_free_rooms') }}</span>
      </div>
      <div class='options' v-if='free.length > 0'>
        <div class='placeholder'>
          {{ __('hotel.add_room') }}
      
        </div>
        <opt v-for='option in free' :id='option.index'></opt>
      </div>
    </template>
      <div class='results' v-if='rooms.length > 0'>
        <div class='placeholder'>
          {{ __('hotel.added_rooms') }}
      
        </div>
        <div class='results-container'>
          <room v-for='(room, index) in rooms' :id='index'></room>
        </div>
      </div>
  </div>
</template>

<script>
  import Room from './Room.vue';
  import Opt from './Option.vue';

  import {mapState, mapGetters} from 'vuex';

  export default {
    components: {
      Opt, Room
    },

    computed: {
      ...mapState({
        options: state => state.free,
        rooms: state => state.Cart.rooms,
        loading: state => state.Cart.loading
      }),
      ...mapGetters(['free'])
    }
  }
</script>