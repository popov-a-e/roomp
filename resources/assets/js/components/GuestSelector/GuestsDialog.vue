<template>
  <div class='guests_dialog' @click.prevent.stop="">
    <div class='selectors'>
      <div class='numbers'>
        <plus-selector
          :name='"room_number"'
          :module='"GuestSelector"'
          :mutation='"setRoomNumber"'
          :plural='"common.rooms"'
          :max='maxRoomNumber'
          :min='minRoomNumber'
        ></plus-selector>
      </div>

      <div class='adults'>
        <plus-selector
          :name='"adult_number"'
          :module='"GuestSelector"'
          :mutation='"setAdultNumber"'
          :plural='"common.adults"'
          :max='9'
          :min='1'
        ></plus-selector>
      </div>

      <div class='children'>
        <plus-selector
          :name='"children_number"'
          :module='"GuestSelector"'
          :mutation='"setChildrenNumber"'
          :plural='"common.children"'
          :max='10'
          :min='0'
        ></plus-selector>
      </div>
    </div>

    <div class='children_ages' v-if='children_number > 0'>
      <div class="header">
        <hr/>
        <h4>{{ __("common.guest_selector.age_children") }}</h4>
      </div>
      <scrollable-select
        v-for='(v,index) in Array.from({length: children_number})'
        :values='agesList'
        :get='() => children[index]'
        :set='value => setChildren({value, index})'
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
  import module from './store/GuestSelector';

  export default {
    components: {Cselect, PlusSelector, ScrollableSelect},
    computed: module.mapProps('GuestSelector'),
    methods: module.mapMethods('GuestSelector')
  }
</script>