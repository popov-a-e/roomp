<template>
  <div class='room'>
    <div class='short'>
      <button
        v-on:click='remove'
      ><i class='icon-close'></i></button>
      <div>
        <div>
          <div class='name'>{{ name }}</div>
          <div>{{ guestsFormatted }}</div>
        </div>
        <div>
          <div class='price' v-html="nightsFormatted + ' x ' + toCurrency(price)"></div>
          <a v-if='!editing' href='javascript:void(0);' v-on:click='edit({id: id, room: room})'>{{ __('hotel.change_conditions')}}</a>
        </div>
      </div>
    </div>
    <guests v-if='editing' :id='id'></guests>
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
      ...mapMutations({removeRoom: 'removeRoom', edit: 'CurrentRoom/edit'}),
      remove() {
        this.$store.commit('removeRoom', this.id);
      },
    },
    computed: {
      ...mapState('BackendData', ['policy']),
      ...mapState({
        classes: state => state.BackendData.classes,
        allotments: state => state.BackendData.allotments,
        rooms: state => state.Cart.rooms,
        currentID: state => state.CurrentRoom.id,
        currentType: state => state.CurrentRoom.type,

      }),
      ...mapGetters('Cart', ['nightsFormatted']),
      ...mapGetters('BackendData', ['adultsList', 'childrenList']),
      editing () {
        return this.id === this.currentID && this.currentType === 'edit';
      },
      price() {
        const room = this.room;
        const capacity = Object.keys(room.room.prices).length;
        const policy = this.policy;
        const regularAdultNumber = Math.min(capacity, room.adults);

        const extraAdultNumber = room.adults - regularAdultNumber;

        const priceAdults = room.room.prices[regularAdultNumber] + extraAdultNumber * policy.price_adults;
        const priceChildren = room.children * policy.price_children;
        const priceInfants = room.infants * policy.price_infants;

        return priceAdults + priceChildren + priceInfants;
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