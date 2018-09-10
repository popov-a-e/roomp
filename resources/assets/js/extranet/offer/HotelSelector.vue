<template>
  <div class="hotel-selector" @click.stop="toggle">
    <span>{{hotels.where('id', id).name}}</span>
    <div v-if="menuVisible">
      <a :href="`/select_hotel/${hotel.id}`" v-for="hotel in hotels">{{hotel.name}}</a>
      <a href='/logout'>{{__('extranet.header.log_out')}}</a>
    </div>
  </div>
</template>

<script>
  import {mapState, mapActions, mapGetters, mapMutations} from 'vuex';
  import Bus from '../../Bus';

  export default {
    props: ['hotels', 'id'],
    data() {
      return {
        menuVisible: false
      }
    },
    methods: {
      toggle() {
        this.menuVisible = !this.menuVisible;
      }
    },
    created() {
      Bus.$on('click', () => this.menuVisible = false);
    }
  }
</script>  