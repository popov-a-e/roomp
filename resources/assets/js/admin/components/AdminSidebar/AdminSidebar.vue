<template>
  <div class='sidebar'>
    <div v-for='route in routes'>
      <router-link :to="route" >{{ route.meta.name }}</router-link>
      <div v-for='route in route.children' v-if='route.meta' >
        <router-link :to="`/${route.path}`" >{{ route.meta.name }}</router-link>
  
        <template v-if='links[route.path]'>
          <recursive-link v-for='link in links[route.path].children' :link='Object.assign({}, link)'></recursive-link>
        </template>
      </div>
    </div>
  </div>
</template>

<script>
  import RecursiveLink from './RecursiveLink.vue';
  import { mapState, mapActions, mapMutations, mapGetters } from 'vuex';
  
  export default {
    components: {
      RecursiveLink
    },
    data () {
      return {}
    },
    computed: {
      routes () {
        return this.$router.options.routes.filter(r => !r.redirect);
      },
      ...mapState('Router',['links'])
    },
    methods: {
      routeClose(route) {
        const component = this.$router.resolve(route.path).route.matched[2].instances.default;
        
        this.$store.dispatch('Router/routeClose', route);
        
        component.ID = route.id;

        component.updateModule();
        component.remove();
      }
    }
  }
</script>