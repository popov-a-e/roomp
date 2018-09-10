<template>
  <div class='return-back'>
    <div class='info'>
      <p>{{ infoString }}</p>
      <p>{{ fromFormattedShort }} - {{ tillFormattedShort }}</p>
    </div>
    <button v-on:click='toggle'>
      {{__("hotel.change")}}
    
    </button>
  </div>
</template>

<script>
  import {mapState, mapActions, mapMutations, mapGetters} from 'vuex';

  export default {
    computed: {
      ...mapGetters('Cart', ['infoString', 'fromFormattedShort', 'tillFormattedShort'])
    },
    methods: {
      ...mapMutations(['toggle'])
    }
  }
</script>