<template>
  <div class='login' v-on:click.stop='Bus.$emit("click",_uid)'>
    <button class='close' v-on:click='hide'><i class='icon-close'></i></button>

    <h2>{{ __("header.hi") }}</h2>

    <div class='form'>
      <country-select v-if='!mobile' :phone-init='phone' @input='setPhone'></country-select>
      <phone-input v-else :phone-init='phone' @input='setPhone'></phone-input>
      <input v-model='password' @keydown='keydown' name='password' type='password' :placeholder='__("header.password")'/>

      <label><input type='checkbox' name='remember' v-model='remember'/>{{ __("header.remember_me")}}</label>
      <a href='javascript:void(0)' v-on:click='toForgot'>{{ __("header.forgot_password") }}</a>
    </div>

    <button class='confirm' @click='login({phone: phone,password,remember})'>
      {{ __("header.log_in") }}
    </button>

    <p>{{ __("header.new_user") }} <a href='javascript:void(0);' v-on:click='toRegister'>{{ __("header.register") }}</a></p>
  </div>
</template>

<script>
  import { mapActions, mapMutations, mapState } from 'vuex';
  import CountrySelect from './CountrySelect.vue';
  import PhoneInput from '../../mobile/components/PhoneInput.vue';
  import Bus from './../../Bus';

  export default {
    components: {
      CountrySelect, PhoneInput
    },
    data() {
      return {
        password: null,
        remember: false,
        Bus: Bus
      }
    },
    methods: {
      ...mapActions('Header', ['login']),
      ...mapMutations('Header', ['setPhone']),
      ...mapMutations('Header/Appearance', ['hide','toRegister', 'toForgot']),
      keydown(e) {
        if (e.which === 13) {
          this.login({phone: this.phone,password: this.password,remember: this.remember});
        }
      }
    },
    computed: {
      ...mapState('Header', ['mobile', 'phone'])
    },

  }
</script>