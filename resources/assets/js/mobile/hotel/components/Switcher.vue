<template>
  <div class='switcher'>
    <hr/>

    <button v-on:click='toggle' v-bind:disabled='cartEmpty'>{{ __('hotel.book') }}</button>
  </div>
</template>

<script>
  import  {mapMutations, mapGetters} from 'vuex';

  export default {
    methods: mapMutations(['toggle']),
    computed: mapGetters(['cartEmpty'])
  }
</script>