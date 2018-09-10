<template>
  <header>
    <div class="header-container">
      <a href="#/" class="logo"></a>
      <div v-if="hotel">
        <router-link :to="'dashboard'">{{__('extranet.header.calendar')}}</router-link>
        <router-link :to="'reservations'">{{__('extranet.header.reservations')}}</router-link>
        <router-link :to="'reconciliation'">{{__('extranet.header.reconciliation')}}</router-link>
        <router-link :to="'finance'">{{__('extranet.header.finance')}}</router-link>

        <div class="header-menu" @click.stop="toggle">
          <span>{{hotels.where('id', hotel.id).regular_name}}</span>
          <div v-if="menuVisible">
            <a :href="`/select_hotel/${hotel.id}`" v-for="hotel in hotels">{{hotel.regular_name}}</a>
            <a style="" href="#/booking_confirmation">{{__('extranet.header.booking_confirmation')}}</a>
            <a style="" href="#/organization">{{__('extranet.header.organization')}}</a>
            <a target="_blank" href="/docs/offer">{{__('extranet.header.offer')}}</a>
            <a v-if='reminder' target="_blank" href="/docs/reminder">{{__('extranet.header.reminder')}}</a>
            <a v-if='instruction' target="_blank" href="/docs/instruction">{{__('extranet.header.instruction')}}</a>

            <!--
            <div>{{__('extranet.header.settings')}}</div>
            <div>{{__('extranet.header.property')}}</div>
            <router-link :to="'property'">{{__('extranet.header.property')}}</router-link>-->
            <a href='/logout'>{{__('extranet.header.log_out')}}</a>
          </div>
        </div>
      </div>
      <lang-select :value='locale' @input='changeLocale'></lang-select>
    </div>
    <!-- <cselect class='lang' :options='hotels' :selected='hotel.id' :name='`Ваши отели`' @input='setHotel'></cselect> -->
  </header>
</template>

<script>
  import {mapState, mapActions, mapMutations, mapGetters} from 'vuex';
  import Bus from '../../../Bus';
  import LangSelect from '../../../components/RoompHeader/LangSelect.vue';

  export default {
    data () {
      return {
        dropdown: false,
      }
    },
    components: {LangSelect},
    props: ['user', 'hotels', 'hotel', 'locale', 'instruction', 'reminder'],
    computed: {
      ...mapState('Header', ['menuVisible'])
    },
    methods: {
      ...mapMutations('Header', ['initialize', 'setHotels', 'toggleMenu', 'hideMenu']),
      ...mapActions('Header', ['setHotel', 'changeLocale']),

      toggle() {
        this.toggleMenu();
        Bus.$emit('click', this._uid);
      },

    },
    created () {
      this.initialize({
        hotels: this.hotels,
        hotel: this.hotel
      });

      Bus.$on('click', _uid => {
        if(this._uid !== _uid) this.hideMenu()
      });
    }
  }
</script>