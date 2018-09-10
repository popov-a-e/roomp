<template>
  <header>
    <a class='logo' href='/'></a>
    
    <a href='/logout' class='user'>{{ user.name }} : Выйти</a>
  </header>
</template>

<script>
  import { mapState, mapActions, mapMutations, mapGetters } from 'vuex';

  export default {
    props: ['user'],
    computed: {
    },
    methods: {
      ...mapMutations('Header', ['initialize'])
    },
    created () {
      this.initialize(this.user);
      this.$store.commit('Router/initRouter', this.$router);
      this.$router.afterEach(to => {
        this.$store.commit('Router/navigateTo', to);
      });
    }
  }
</script>