<template>
  <div class='room'>
    <div class='short'>
      <button
        v-on:click='remove'
      ><i class='icon-close'></i></button>
      <div class='description'>
        <div class='name'>{{ name }}</div>
        <div>{{ guestsFormatted }}</div>
      </div>
      <div class='price'>
        <div v-html="nightsFormatted + ' x ' + toCurrency(price)"></div>
        <a href='javascript:void(0);' v-on:click='edit({id: id, room: room})'>{{ __("hotel.change_conditions") }}</a>
      </div>
    </div>
  </div>
</template>

<script>
  import {mapState, mapGetters, mapMutations} from 'vuex';
  import Guests from './Guests.vue';

  export default {
    components: {
      Guests
    },
    props: ['id'],
    methods: {
      ...mapMutations(['removeRoom']),
      ...mapMutations('CurrentRoom', ['unpick', 'edit']),
      remove() {
        this.removeRoom(this.id);
      },
    },
    computed: {
      ...mapState({
        classes: state => state.BackendData.classes,
        allotments: state => state.BackendData.allotments,
        rooms: state => state.Cart.rooms,
        currentID: state => state.CurrentRoom.id,
        currentType: state => state.CurrentRoom.type,

      }),
      ...mapGetters('Cart', ['nightsFormatted']),
      ...mapGetters('BackendData', ['adultsList', 'childrenList']),
      ...mapGetters('CurrentRoom', ['price']),
      editing () {
        return this.id === this.currentID && this.currentType === 'edit';
      },
      room () {
        return this.rooms[this.id];
      },
      className () {
        return this.classes.where('id', this.room.class).name;
      },
      allotmentName () {
        return this.allotments.where('id', this.room.allotment).name;
      },
      name () {
        return this.className + ', ' + this.allotmentName
      },
      guestsFormatted () {
        let adults = this.adultsList.where('id', this.room.adults).name;
        let children = this.childrenList.where('id', this.room.children).name;

        return `${adults}, ${children}`;
      }
    }
  }
</script>