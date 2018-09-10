<template>
  <div class='rooms'>
    <dates></dates>
    <picker></picker>
    <bill v-if='rooms.length > 0'></bill>
    <switcher></switcher>
  </div>
</template>

<script>
  import Dates from './Dates.vue';
  import Picker from './Picker/Picker.vue';
  import Bill from './../Bill.vue';
  import Switcher from './../Switcher.vue';
  import { mapState } from 'vuex'

  export default {
    components: {
      Dates, Picker, Bill, Switcher
    },
    computed: mapState('Cart', ['rooms'])
  }
</script>