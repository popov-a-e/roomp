<template>
  <div class='guests_dialog' @click.prevent.stop=''>
    <div class='selectors'>
      <div class='rooms'>
        <cselect
          :name='__("common.guest_selector.room_number")'
          :selected='room_number'
          :options='roomNumbers'
          @input='setRoomNumber'
        ></cselect>
      </div>
      <div class='rooms'>
        <cselect
          :name='__("common.guest_selector.adult_number")'
          :selected='adult_number'
          :options='adultNumbers'
          @input='setAdultNumber'
        ></cselect>
      </div>
      <div class='rooms'>
        <cselect
          :name='__("common.guest_selector.children_number")'
          :selected='children_number'
          :options='childrenNumbers'
          @input='setChildrenNumber'
        ></cselect>
      </div>
    </div>
      
    <div class='children_ages' v-if='children_number > 0'>
      <h4>{{ __("common.guest_selector.age_children") }}</h4>
      <cselect
        v-for='(v, index) in Array.from({length: children_number})'
        :name='__("common.guest_selector.age_child")'
        :selected='children[index]'
        :options='agesList'
        @input='value => setChildren({index, value})'
      ></cselect>
    </div>
  </div>
</template>

<script>
  import Cselect from '../../../mobile/components/Cselect.vue';
  import {mapState, mapActions, mapMutations, mapGetters} from 'vuex';
  import module from './../store/GuestSelector';

  export default {
    components: {Cselect},
    computed: {
      ...module.mapProps('GuestSelector'),
      maxRoomNumber () {
        return this.adults_number;
      },
      minRoomNumber () {
        return Math.ceil(this.adults_number / 2);
      },
      agesList () {
        return Array.from({length: 17}).map((v,i) => window.pluralize("common.years", i));
      },
      adultNumbers () {
        return Array.from({length: 9}).map((v, i) => ({
          id: i + 1,
          name: window.pluralize("common.adults", i + 1)
        }));
      },
      childrenNumbers () {
        return Array.from({length: 9}).map((v, i) => window.pluralize("common.children", i));
      },
      roomNumbers() {
        return Array.from({length: 9}).map((v, i) => ({
          id: i + 1,
          name: window.pluralize("common.rooms", i + 1)
        }));
      }
    },
    methods: {
      ...module.mapMethods('GuestSelector'),
      pluralize: window.pluralize
    }
  }
</script>