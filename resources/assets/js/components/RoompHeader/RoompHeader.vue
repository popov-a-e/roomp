<template>
  <header>
    <a href='/' class='logo'></a>
    
    <div class='user'>
      <button class='profile' v-bind:class='{active: menuVisible}' v-on:click='toggleMenu' v-if='username'>
        {{ username }}
      </button>
      <user-menu v-if='username && menuVisible'></user-menu>
      <button
        class='login'
        v-if='!username'
        v-on:click='show'
      >
        {{__('header.log_in')}}
      </button>
    </div>
    <a class='about' href='/about'>{{__('header.about')}}</a>
    <span class='phone'>{{ __('header.support_phone') }}</span>
    <currency-selector></currency-selector>
    <lang-select :value='localeState' @input='changeLocale'></lang-select>
    <button @click='togglePromoOverlay' v-if='promoOverlay'>{{ __("home.how_it_works") }}</button>
    <overlay v-if='visible'></overlay>
  </header>
</template>

<script>
  import Overlay from './Overlay.vue';
  import UserMenu from './UserMenu.vue';
  import {mapMutations, mapState, mapActions} from 'vuex';
  import Cselect from '../Cselect.vue';
  import LangSelect from './LangSelect.vue';
  import CurrencySelector from './CurrencySelector.vue';

  export default {
    components: {
      Overlay, UserMenu, Cselect, LangSelect, CurrencySelector
    },
    props: ['user', 'locale', 'promoOverlay', 'currency', 'currencies', 'locales'],
    methods: {
      ...mapMutations('Header/Appearance', ['show', 'toggleMenu', 'togglePromoOverlay']),
      ...mapMutations('Header', ['setUser', 'setMobile', 'setLocale', 'setSettings', 'setCurrency']),
      ...mapActions('Header', ['changeLocale'])
    },
    computed: {
      username () {
        return this.user ? this.user.name : false;
      },
      ...mapState('Header/Appearance', ['visible', 'menuVisible']),
      ...mapState('Header', {localeState: 'locale'}),
    },
    created () {
      this.setUser(this.user);
      this.setMobile(false);
      this.setLocale(this.locale);
      this.setCurrency(this.currency);
      this.setLocales(this.locales);
      this.setCurrencies(this.currencies);
    }
  }
</script>