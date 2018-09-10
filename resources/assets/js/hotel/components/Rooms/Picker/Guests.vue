<template>
  <div class='guests'>
    <div class='sel-group'>
      <label>{{__('common.adults_select')}}: </label>
      <plus-selector
        :module='"CurrentRoom"'
        :name='"adults"'
        :mutation='"setAdults"'
        :max='adultsMax'
        :min='1'
      >
      </plus-selector>
      <p v-html="toCurrency(priceForAdults)"></p>
    </div>
    <div class='sel-group' v-if="childrenMax > 0">
      <label>{{__('common.children_interval', {age: maxAge})}}: </label>
      <plus-selector
        :module='"CurrentRoom"'
        :name='"children"'
        :mutation='"setChildren"'
        :max='childrenMax'
        :min='0'
      >
      </plus-selector>
      <p v-html="toCurrency(priceForChildren)"></p>
    </div>
    <div class='sel-group' v-if="infantsMax > 0">
      <label>{{__('common.children_low') }}: </label>
      <plus-selector
        :module='"CurrentRoom"'
        :name='"infants"'
        :mutation='"setInfants"'
        :max='infantsMax'
        :min='0'
      >
      </plus-selector>
      <p v-html="toCurrency(priceForInfants)"></p>
    </div>

    <div class='price'>
      <p>{{ __("common.total") }}</p>
      <p v-html="toCurrency(price)"></p>
    </div>
    <div class="buttons">
      <button class="apply" v-on:click='commitRoom'>
        <span v-if='type === "add"'>{{__('hotel.add') }}</span>
        <span
        v-else>{{ __('hotel.apply') }}</span>
      </button>
      <button class="cancel" v-on:click="unpick">{{ __('hotel.cancel') }}</button>
    </div>
  </div>
</template>

<script>
  import Cselect from './../../../../components/Cselect.vue';
  import PlusSelector from './../../../../components/PlusSelector.vue';
  import {mapState, mapActions, mapMutations, mapGetters} from 'vuex';

  export default {
    components: {Cselect, PlusSelector},
    methods: {
      ...mapMutations('CurrentRoom', ['setChildren', 'setAdults', 'setInfants', 'unpick']),
      ...mapMutations(['commitRoom'])
    },
    computed: {
      ...mapState('BackendData', ['policy']),
      ...mapState('CurrentRoom', ['room', 'children', 'infants', 'adults', 'type']),
      ...mapGetters('CurrentRoom', ['price', 'adultsList', 'childrenList', 'infantList', 'childrenMax', 'adultsMax', 'infantsMax', 'priceForChildren', 'priceForAdults', 'priceForInfants']),
      maxAge() {
        return this.policy.age_children;
      }
    }
  }
</script>