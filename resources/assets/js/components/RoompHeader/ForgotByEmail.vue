<template>
  <div class='forgot-by-email' v-on:click.stop='Bus.$emit("click",_uid)'>
    <button class='close' v-on:click='hide'><i class='icon-close'></i></button>
    <h2>{{ __("header.password_reset") }}</h2>
    <input v-model='email' :placeholder='__("header.insert_reset_email")' />
    <button v-on:click='resetPasswordByEmail(email)'>{{ __("header.send_email_link") }}</button>
    <button class='back' v-on:click='toLogin'>{{ __("header.back") }}</button>
  </div>
</template>

<script>
  import Bus from './../../Bus';
  import {mapActions, mapMutations} from 'vuex';
  
  export default {
    data () {
      return {
        Bus,
        email: ''
      }
    },
    methods: {
      ...mapActions('Header', ['resetPasswordByEmail']),
      ...mapMutations('Header/Appearance', ['toLogin', 'hide'])
    }
  }
</script>