<template>
  <div class='form'>
    <cselect :options='cities' :name='__("home.select_city")' :selected='city' @input='e => city = e'></cselect>
    <a :href='`/search${city ? `?city=${city}` : ""}`'>{{ __("home.search") }}</a>
  </div>
</template>

<script>
  import Cselect from '../../../components/Cselect.vue';
  import { mapState, mapActions, mapMutations, mapGetters } from 'vuex';

  export default {
    components: {
      Cselect
    },
    data () {
      return {
        city: null
      }
    },
    props: ['params'],
    computed: {
      ...mapState(['cities'])
    },
    methods: {
      ...mapMutations(['initialize'])
    },
    created( ) {
      this.initialize(this.params);
    }
  }
</script>