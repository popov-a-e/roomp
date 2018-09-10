<template>
  <main
    v-on:click='Bus.$emit("click")'
    v-on:mouseup='Bus.$emit("mouseup")'
    v-on:mousemove.prevent='(e) => Bus.$emit("mousemove",e.pageX, e.pageY, e)'
    v-on:wheel='e => Bus.$emit("wheel", e, _uid)'
  >
    <roomp-header :locale='locale' :user='user' :currency="currency" :settings="settings"></roomp-header>
    <sidebar></sidebar>

    <g-map :hotels='hotels'
           :center='cityData'>
    </g-map>
  </main>
</template>

<script>
  import Sidebar from './Sidebar.vue';
  import GMap from '../../components/GMap/GMap.vue';
  import RoompHeader from '../../components/RoompHeader/RoompHeader.vue';

  import Bus from './../../Bus';

  import {mapState, mapMutations, mapGetters} from 'vuex';

  export default {
    data () {
      return {
        Bus
      }
    },
    components: {
      Sidebar, GMap, RoompHeader
    },
    props: ['params','request', 'user', 'locale', 'currency', 'settings', 'maxPrice'],
    computed: {
      ...mapGetters(['cityData']),
      ...mapState(['hotels'])
    },
    methods: {
      ...mapMutations('BackendData', {initializeBackendData: 'initialize'}),
      ...mapMutations('Filters', {initializeFilters: 'initialize'}),
    },
    created () {
      this.initializeBackendData(this.params);
      this.initializeFilters(this.request);

      this.$store.commit('GuestSelector/seedChildren', this.request.children || []);
      this.$store.commit('GuestSelector/setChildrenNumber', this.request.children_number);
      this.$store.commit('GuestSelector/setAdultNumber', this.request.adult_number);
      this.$store.commit('GuestSelector/setRoomNumber', this.request.room_number);
      this.$store.commit('Filters/setMaxPrice', this.maxPrice);

      window.addEventListener('resize', () => Bus.$emit('resize'));

      Bus.$emit('mounted');
      
      Bus.$on('wheel', (e, _uid) => {
        if (this._uid === _uid) e.preventDefault();
      });
    }
  }
</script>