<template>
  <div class="payment-status-icon">
    <i v-if="type==='unpaid'" class="fa fa-exclamation-circle" style="color: red; font-size: 25px;"></i>
    <i v-else-if="type==='paid'" class="fa fa-check-circle" style="color: green;  font-size: 25px;"></i>
    <div v-else class="logo">
      <div class="container">
        <div class="circle-top"></div>
        <div class="circle-bot"></div>
      </div>
    </div>
  </div>
</template>

<script>
  import {mapState, mapActions, mapGetters, mapMutations} from 'vuex';

  export default {
    props: ['type']
  }
</script>  