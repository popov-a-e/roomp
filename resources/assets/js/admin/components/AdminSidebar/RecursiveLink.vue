<template>
  <div>
    <router-link :to='link.route.path' tag='div' class='router-link'>
      <a>{{routeName}}</a>
      <button @click='routeClose(link.route)'>
        <i class='icon-close'></i>
      </button>
    </router-link>
    
    <recursive-link v-for='_link in link.children' :link='_link' :key='_link.route.path'></recursive-link>
  </div>
</template>

<script>
  import { mapState, mapActions, mapMutations, mapGetters } from 'vuex';

  export default {
    name: 'recursive-link',
    props: ['link'],
    methods: {
      ...mapActions('Router',['routeClose']),
      loadMeta () {
        this.$store.dispatch('Router/loadMetaData', {name: route.meta.resource, id: this.link.route.id});
        
      }
    },
    computed: {
      route () {
        return this.$router.resolve(this.link.route.path).route;
      },
      routeName () {
        const route = this.route;
        if (!route.meta.state && !route.meta.resource) return this.link.route.name;
        if (this.routeNameDefault) return this.routeNameDefault;
        
        const name = this.$store.state.Router[route.meta.resource][this.link.route.id];
        
        if (!name) {
          this.$store.dispatch('Router/loadMetaData', {name: route.meta.resource, id: this.link.route.id, params: this.route.params});
          return 'Loading...';
        }
        
        return name || 'No name';
      },
      routeNameDefault () {
        const route = this.route;
        
        if (!route.meta.state) return false;

        let name = this.$store.state;
        
        let state = route.meta.state;
        state = state.replace(/\$([\w_])+/g, e => {
          return route.params[e.replace('$','')];
        });
        
        state.split('.').forEach(step => name = name ? name[step] : false);
        
        if (name === false) {
          return false;
        } else {
          if (name instanceof Array) return name.where('id', this.link.route.id * 1).name;
          
          return name.name;
        }
      }
    }
  }
</script>