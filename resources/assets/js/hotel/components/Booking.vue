<template>
  <div class='booking' @wheel='wheel' >
    <div class='header'>
      <h2>{{ __('hotel.hotel_booking') }}</h2>
    </div>
    <rooms v-if='!rooms_selected'></rooms>
    <book v-if='rooms_selected'></book>
    <div class='scrollbar' v-show='scrollingActive' @mousedown='scrollBarMouseDown' :style='{top: scrollTop + "px"}'></div>
  </div>
</template>

<script>
  import Rooms from './Rooms/Rooms.vue'
  import Book from './Book/Book.vue';
  import Bus from '../../Bus';
  
  export default {
    props: ['params', 'request', 'hotel_id', 'payment-offline', 'payment-online', 'has-breakfast', 'policy'],
    components: {
      Rooms, Book
    },
    data () {
      return {
        scrollBarActive: false,
        scrollingActive: false,
        scTop: 0,
        scrollTopFix: 0
      }
    },
    created () {
      this.$store.commit('BackendData/setPolicy', this.policy);
      this.$store.commit('BackendData/initialize', this.params);
      this.$store.commit('Cart/initialize', this.request);
      this.$store.commit('Appearance/checkClass', this.$store.state.BackendData.classes[0]);
      this.$store.commit('initialize', this.hotel_id);
      this.$store.dispatch('updateRooms');
      this.$store.commit('setPaymentOnline', this.paymentOnline);
      this.$store.commit('setPaymentOffline', this.paymentOffline);
      this.$store.commit('setBreakfast', this.hasBreakfast);
    },
    mounted () {
      Bus.$on('resize', this.updateLocalScroll);

      Bus.$on('mousemove', (x,y,e) => {
        if (e.which !== 1) {
          this.scrollBarActive = false;
          return true;
        }
        
        if (!this.scrollBarActive) return true;

        e.preventDefault();
        const div = this.$el;

        let delta = y - this.y;
        const scrollHeight = div.scrollHeight - div.offsetHeight;

        delta /= (div.offsetHeight - 140) / scrollHeight;
        div.scrollTop = Math.min(scrollHeight, this.scrollTopFix + delta);
        this.scTop = div.scrollTop;
      });

      const target = this.$el;
      
      window.updateLocalScroll = this.updateLocalScroll;
      
      if (window.MutationObserver) {
        new MutationObserver(() => this.updateLocalScroll()).observe(target, {subtree: true, childList: true});
      } else {
        this.$el.addEventListener('DOMNodeInserted DOMNodeRemoved', this.updateLocalScroll);
      }
    },
    computed: {
      rooms_selected () {
        return this.$store.state.Appearance.rooms_selected;
      },
      scrollTop () {
        if (!this.$el) return this.scTop - this.scTop * 1;
        const scrollHeight = this.$el.scrollHeight - this.$el.offsetHeight;

        return this.scTop + (this.scTop / scrollHeight) * (this.$el.offsetHeight - 140);
      }
    },
    methods: {
      updateLocalScroll() {
        if (!this.$el) return;
        this.scrollingActive = this.$el.scrollHeight > this.$el.offsetHeight;
        if (!this.scrollingActive) {
          this.scrollBarActive = false;
        }
        
        window.$el = this.$el;
      },
      wheel (e) {
        if (!this.scrollingActive) return true;
        e.preventDefault();
        e.stopPropagation();
        const div = this.$el;
        const scrollHeight = this.$el.scrollHeight - this.$el.offsetHeight;

        div.scrollTop = Math.min(scrollHeight, div.scrollTop + e.deltaY);
        this.scTop = div.scrollTop;
      },

      scrollBarMouseDown (e) {
        this.scrollBarActive = true;
        this.y = e.pageY;
        this.scrollTopFix = this.$el.scrollTop;
      }
    },
    updated () {
      this.$store.dispatch('updateScroll');
    }
  }
</script>