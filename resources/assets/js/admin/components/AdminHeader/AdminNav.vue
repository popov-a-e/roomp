<template>
  <nav>
    <router-link tag='div' class='tab-link' v-for='link in links' :to='link.path'>
      <a>{{link.name}}</a>
      <button v-if='links.length > 1' v-on:click='routeClose(link.path)'><i class='icon-close'></i></button>
    </router-link>
  </nav>
</template>

<script>
  import {mapState, mapActions, mapMutations, mapGetters} from 'vuex';

  export default {
    data () {
      return {}
    },
    computed: {
      ...mapState('Header', ['links'])
    },
    methods: {
      ...mapMutations(['routeClose'])
    }
  }
</script>