<template>
  <div class="allotments">
    <h2>{{__('search.type')}}</h2>
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
  </div>
</template>

<script>
  import Cbutton from '../../../components/Cbutton.vue';
  import {mapMutations, mapState} from 'vuex';

  export default {
    methods: mapMutations('Filters',['toggleAllotment']),
    computed: mapState({
      allotments: state => state.Filters.allotments,
      allotmentsArray: state => state.BackendData.allotments
    }),
    components: {
      Cbutton
    }
  }
</script>