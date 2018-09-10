<template>
  <div class='guests' @click.stop.prevent=''>
    <div class='room-name'>
      {{ name }}
    </div>
    
    <div class='sel-group'>
      <label>Взрослые: </label>
      <cselect
        class='adults'
        v-on:input="setAdults"
        :selected="adults"
        :options="adultsArray"
      ></cselect>
      <p v-html="toCurrency(priceForAdults)"></p>
    </div>
    <div class='sel-group'v-if="policy.price_children !== null">
      <label>Дети от 2 до {{policy.children_age}} лет: </label>
      <cselect
        class='children'
        v-on:input="setChildren"
        :selected="children"
        :options="childrenArray"
      ></cselect>
      <p v-html="toCurrency(priceForChildren)"></p>
    </div>
    <div class='sel-group' v-if="policy.price_infants !== null">
      <label>Дети до 2 лет: </label>
      <cselect
        class='infants'
        v-on:input="setInfants"
        :selected="infants"
        :options="infantArray"
      ></cselect>
      <p v-html="toCurrency(priceForInfants)"></p>
    </div>
    
    <div class='price' v-html="toCurrency(price)"></div>
    <div class='buttons'>
      <button v-on:click='commitRoom'><span v-if='type === "add"'>{{ __('hotel.add') }}</span><span
        v-else>{{ __('hotel.apply') }}</span></button>
      <button @click='unpick'>{{ __('hotel.cancel') }}</button>
    </div>
  </div>
</template>

<script>
  import Cselect from './../../../../components/Cselect.vue';
  import {mapState, mapActions, mapMutations, mapGetters} from 'vuex';

  export default {
    components: {Cselect},
    methods: {
      ...mapMutations('CurrentRoom', ['setChildren', 'setAdults', 'setInfants', 'unpick']),
      ...mapMutations(['commitRoom'])
    },
    computed: {
      ...mapState('BackendData', ['classes', 'allotments', 'policy']),
      ...mapState('CurrentRoom', ['room', 'children', 'adults', 'type', 'allotment', 'infants']),
      ...mapGetters('CurrentRoom', ['price', 'adultsArray', 'childrenArray', 'infantArray', 'priceForAdults', 'priceForChildren','priceForInfants']),
      className () {
        return this.classes.where('id', this.room.room_class_id).name;
      },
      allotmentName () {
        return this.allotments.where('id', this.allotment).name;
      },
      name () {
        return this.className + ', ' + this.allotmentName
      },
    },
  }
</script>