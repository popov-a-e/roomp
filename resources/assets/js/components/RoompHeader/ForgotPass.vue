<template>
  <div class='forgot-pass' v-on:click.stop='Bus.$emit("click",_uid)'>
    <h2>
      {{ __("header.password_reset") }}
    </h2>
  
  
    <button v-on:click='toForgotByPhone'>{{ __("header.reset_by_phone") }}</button>
    <button v-on:click='toForgotByEmail'>{{ __("header.reset_by_email") }}</button>
    <button class='back' v-on:click='toLogin'>{{ __("header.back") }}</button>
  </div>
</template>

<script>
  import {mapMutations} from 'vuex';
  import Bus from './../../Bus';
  
  export default {
    data () {
      return {
        Bus
      }
    },
    methods: mapMutations('Header/Appearance',['toLogin','toForgotByPhone','toForgotByEmail'])
  }
</script>