<template>
  <div @touchstart='touchstart' @touchmove='touchmove' @touchend='touchend' class='menu'
       :style='{height: contentHeight + 10 + "px"}'>
    <template v-show='!visible'>
      <nav>
        <!--:style='{marginLeft}' -->
        <div class='transition' :class='{moving: x}'>
          <a
            v-for='(item,i) in pages'
            @click='selectItem(i)'
            :class='{selected: i=== tab}'
          
          >{{__("footer." + item.name )}}</a>
        </div>
      </nav>
      <div class='tabs'
           :style='{marginLeft: tabsMarginLeft}' :class='{moving: this.x}'>
        <div
          class='tab'
          v-for='(item,i) in pages'
        >
          <a v-for='page in item.pages' :href='page.link'>{{ __('footer.'+page.name) }}</a>
        </div>
      </div>
      <div class='login'>
        <template v-if='!user'>
          <a @click='show' href='javascript:void(0);'>{{ __('header.log_in') }}</a>
          <a @click='showRegister' href='javascript:void(0);'>{{ __('header.register') }}</a>
        </template>
        <template v-else>
          <a href='/profile#/'>{{ __('header.profile') }}</a>
          <a href='/profile#/reservations'>{{ __('header.my_bookings') }}</a>
          <a href='/auth/logout'>{{ __('header.logout') }}</a>
        </template>
        <cselect :options='languages' :selected='locale' :name='__("header.select_lang")' @input='changeLocale'></cselect>
        <cselect :options='currencies' :selected='currency' :name='__("header.currencies")' @input='changeCurrency'></cselect>
      </div>
      <div class='social'>
      <span class="social">
        <a href="https://vk.com/roomp" target="blank"><i class="fab fa-vk"></i></a>
        <a href="https://www.facebook.com/roomp.co/" target="blank"><i class="fab fa-facebook"></i></a>
        <a href="https://twitter.com/roompofficial" target="blank"><i class="fab fa-twitter"></i></a>
        <a href="https://www.instagram.com/roomp.co/" target="blank"><i class="fab fa-instagram"></i></a>
      </span>
      </div>
    </template>
    <overlay v-if='visible'></overlay>
  </div>
</template>

<script>
  import {mapState, mapActions, mapMutations, mapGetters} from 'vuex';
  import pages from '../../../components/RoompFooter/pages';
  import Overlay from '../../../components/RoompHeader/Overlay.vue';
  import Cselect from '../Cselect.vue';

  export default {
    components: {
      Overlay, Cselect
    },
    data () {
      return {
        tab: 0,
        tabWidth: 120,
        x: false,
        d: 0,
        timestamp: 0,
        pages,
        offsetWidth: 0
      }
    },
    computed: {
      ...mapState('Header', ['contentHeight', 'languages', 'locale', 'user', 'currency', 'currencies']),
      ...mapState('Header', {currencies: state => state.currencies.map(currency => ({id: currency, name: currency}))}),
      ...mapGetters('Header', ['language']),
      ...mapState('Header/Appearance', ['visible']),
      marginLeft () {
        return -((this.tab - this.d) * this.tabWidth) + "px";
      },
      tabsMarginLeft () {
        return -((this.tab - this.d) * this.offsetWidth) + "px";
      }
    },
    methods: {
      ...mapMutations('Header', ['setLocale']),
      ...mapActions('Header', ['changeLocale', 'changeCurrency']),
      ...mapMutations('Header/Appearance', ['show', 'toRegister']),
      selectItem(i) {
        this.tab = i;
      },
      showRegister() {
        this.show();
        this.toRegister();
      },
      touchstart (e) {
        this.x = e.touches[0].pageX;
        this.timestamp = e.timeStamp;
      },
      touchmove(e) {
        this.d = (e.touches[0].pageX - this.x) / this.offsetWidth;
      },
      touchend (e) {
        this.x = false;
        const time = e.timeStamp - this.timestamp;

        if (Math.abs(this.d) > Math.log(time) / 30) {
          this.tab = Math.min(Math.max(0, this.tab - Math.sign(this.d)), this.pages.length - 1);
        }
        this.d = 0;
      }
    },
    mounted () {
      this.offsetWidth = this.$el.querySelector('.tabs') ? this.$el.querySelector('.tabs').offsetWidth : 0;
    },
  }
</script>