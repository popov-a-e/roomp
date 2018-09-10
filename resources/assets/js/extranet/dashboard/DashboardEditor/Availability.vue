<template>
  <div class="availability">
    <h3>{{__('extranet.calendar_changes.availability')}}:</h3>
    
    <input :value="available" @input="e => setAvailability(e.target.value) "/>
  </div>
</template>

<script>
  import {mapState, mapMutations, mapGetters} from 'vuex';

  export default {
    computed: {
      ...mapGetters('DashboardEditor', ['available']),
    },
    methods: {
      ...mapMutations('DashboardEditor', ['setAvailability']),
    },
  }
</script>