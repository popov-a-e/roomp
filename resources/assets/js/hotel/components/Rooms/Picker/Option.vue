<template>
  <div class='option' v-bind:class='{picked: picked}'>
    <div class='general' v-on:click='pickClass' >
      <div class='plus'>+</div>
      <div class='room_name'>{{ room_class.name }}, {{ allotment.name }}</div>
      <div class='room_price' v-html="toCurrency(room.prices[1])"></div>
    </div>
    <guests v-if='picked' :id='id'></guests>
  </div>
</template>

<script>
  import { mapState, mapMutations, mapActions } from 'vuex';
  import Guests from './Guests.vue';


  export default {
    components: {
      Guests
    },
    props: {
      id: Number
    },
    methods: {
      pickClass() {
        if (!this.picked) {
          this.switchToAdd();
          this.setAllotment(this.group.allotment);
          this.setRoom(this.room);

          const adults = Math.min(2, this.room.capacity + this.$store.state.BackendData.policy.bed_number);

          this.pick({id: this.id, adults});
        }
        else this.unpick();
      },
      ...mapMutations('CurrentRoom',['pick', 'unpick','setAllotment', 'setRoom', 'switchToAdd'])
    },
    computed: {
      ...mapState({
        roomID: state => state.CurrentRoom.id,
        currentType: state => state.CurrentRoom.type,
        grouped: state => state.grouped,
        classes: state => state.BackendData.classes,
        allotments: state => state.BackendData.allotments
      }),
      picked () {return this.roomID === this.id && this.currentType === 'add'},
      group () {return this.grouped.where('index', this.id)},
      room () {
        return this.group.rooms.map(roomID => this.$store.state.rooms.where('id', roomID)).filter(room => room.count > 0)[0];
      },
      room_class () {return this.classes.where('id', this.group.class)},
      allotment () {return this.allotments.where('id', this.group.allotment)}
    },
    updated () {
      this.$store.dispatch('updateScroll');
    }
  }
</script>