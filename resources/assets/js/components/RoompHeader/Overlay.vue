<template>
  <div
    class='overlay'
    v-on:click.prevent.stop='hide'
  >
    <login v-if='stage==="login"'></login>
    <register v-if='stage==="register"'></register>
    <verify-phone v-if='stage==="verify-phone"'></verify-phone>
    <forgot-pass v-if='stage==="forgot-pass"'></forgot-pass>
    <forgot-by-phone v-if='stage==="forgot-by-phone"'></forgot-by-phone>
    <forgot-by-email v-if='stage==="forgot-by-email"'></forgot-by-email>
  </div>
</template>

<script>
  import Login from './Login.vue';
  import Register from './Register.vue';
  import ForgotPass from './ForgotPass.vue';
  import ForgotByPhone from './ForgotByPhone.vue';
  import ForgotByEmail from './ForgotByEmail.vue';
  import VerifyPhone from './VerifyPhone.vue';
  import Bus from './../../Bus';

  import { mapMutations, mapState } from 'vuex';

  export default {
    components: {
      ForgotPass, ForgotByPhone, ForgotByEmail, Login, Register, VerifyPhone
    },
    methods: {
      ...mapMutations('Header/Appearance',['hide']),
      click () {
        Bus.$emit('click', this._uid);
      }
    },
    computed: {
      ...mapState('Header/Appearance', ['stage'])
    }
  }
</script>