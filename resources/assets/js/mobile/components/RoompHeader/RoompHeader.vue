<template>
  <header>
    <a href='/' class='logo'></a>
    <div class='transition'></div>
    <a class='phone' href='tel:88007771756'><i class='icon-phone'></i></a>
    <button class='menu' @click='toggleMenu'><i :class='{"icon-menu": !menuVisible, "icon-close": menuVisible}'></i></button>
    <roomp-menu v-if='menuVisible'></roomp-menu>
  </header>
</template>

<script>
  import RoompMenu from './RoompMenu.vue';
  import { mapState, mapActions, mapMutations, mapGetters } from 'vuex';

  export default {
    components: {
      RoompMenu
    },
    data () {
      return {
      }
    },
    props: ['user', 'locale', 'currency', 'settings'],
    computed: {
      ...mapState('Header/Appearance', ['visible', 'menuVisible', 'heightFixed'])
    },
    methods: {
      ...mapMutations('Header', ['setUser', 'setLocale', 'setMobile', 'setCurrency', 'setSettings']),
      ...mapMutations('Header/Appearance', ['toggleMenu']),
      ...mapMutations('Header', ['setContentHeight'])
    },
    created () {
      this.setLocale(this.locale);
      this.setUser(this.user);
      this.setCurrency(this.currency);
      this.setMobile(true);
      this.setSettings(this.settings);
      this.$store.commit('Header/Appearance/setMobile');
      window.addEventListener('resize', e => {if (!this.heightFixed) this.setContentHeight()});
      document.addEventListener('scroll', e => {if (!this.heightFixed) this.setContentHeight()});
    }
  }
</script>