<template>
  <div class='guests_dialog'>
    <div class='selectors'>
  
      <div class='numbers'>
        <plus-selector
          :name='"roomNumber"'
          :module='false'
          :mutation='"setRoomNumber"'
          :plural='"common.rooms"'
          :max='maxRoomNumber'
          :min='minRoomNumber'
        ></plus-selector>
      </div>
      
      <div class='adults'>
        <plus-selector
          :name='"adults_number"'
          :module='false'
          :mutation='"setAdultsNumber"'
          :plural='"common.adults"'
          :max='9'
          :min='1'
        ></plus-selector>
      </div>
      
      <div class='children'>
        <plus-selector
          :name='"children_number"'
          :module='false'
          :mutation='"setChildrenNumber"'
          :plural='"common.children"'
          :max='3'
          :min='0'
        ></plus-selector>
      </div>
    </div>
    
    <div class='children_ages' v-if='children_number > 0'>
      <scrollable-select
        v-for='(v,index) in Array.from({length: children_number})'
        :values='agesList'
        :get='() => children_ages[index]'
        :set='value => setChildrenAge({value, index})'
      >
      </scrollable-select>
    </div>
  </div>
</template>

<script>
  import Cselect from '../../components/Cselect.vue';
  import ScrollableSelect from './ScrollableSelect.vue';
  import PlusSelector from '../../components/PlusSelector.vue';
  import {mapState, mapActions, mapMutations, mapGetters} from 'vuex';

  export default {
    components: {Cselect, PlusSelector, ScrollableSelect},
    computed: {
      ...mapState(['children_number', 'adults_number', 'children_ages', 'childrenNumbers', 'adultsNumbers']),
      maxRoomNumber () {
        return this.adults_number;
      },
      minRoomNumber () {
        return Math.ceil(this.adults_number / 2);
      },
      agesList () {
        return Array.from({length: 18}).map((v,i) => window.pluralize("common.years", i));
      }
    },
    methods: {
      ...mapMutations(['setChildrenNumber', 'setChildrenAge']),
      pluralize: window.pluralize
    }
  }
</script>