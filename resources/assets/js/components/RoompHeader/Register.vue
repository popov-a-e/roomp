<template>
  <div class='register' v-on:click.stop='Bus.$emit("click",_uid)'>
    <button class='close' v-on:click='hide'><i class='icon-close'></i></button>

    <h2>{{ __("header.hello") }}</h2>

    <div class='form'>
      <input name='name' v-on:input='e => setName(e.target.value)' v-model='name' :placeholder='__("header.name")'/>
      <div class='errors' v-if='errors.name'>{{ errors.name }}</div>
      <input name='email' v-on:input='e => setEmail(e.target.value)' v-model='email' placeholder='E-mail'/>
      <div class='errors' v-if='errors.email'>{{ errors.email }}</div>
      <country-select v-if='!mobile' @input='setPhone'></country-select>
      <phone-input v-else @input='setPhone'></phone-input>
      <div class='errors' v-if='errors.phone'>{{ errors.phone }}</div>
      <input name='password' v-on:input='e => setPassword(e.target.value)' v-model='password' type='password' :placeholder='__("header.password")'/>
      <div class='errors' v-if='errors.password'>{{ errors.password }}</div>
      <input name='password_confirmation' v-on:input='e => setPasswordConfirmation(e.target.value)' v-model='password_confirmation' type='password' :placeholder='__("header.password_confirmation")'/>
      
      <label><input type='checkbox' v-on:change='e => setAcceptance(e.target.checked)' :checked='acceptance'/> {{ __("header.i_accept") }}&nbsp;<a href='/terms'>{{ __("header.agreement")}}</a></label>
    </div>

    <button class='confirm' @click='register'>
      {{ __("header.register") }}
    </button>

    <p>{{ __("header.registered") }} <a href='javascript:void(0);' @click='toLogin'>{{ __("header.log_in") }}</a></p>
  </div>
</template>

<script>
  import {mapState, mapMutations, mapActions} from 'vuex';
  import CountrySelect from './CountrySelect.vue';
  import Bus from './../../Bus';
  import PhoneInput from '../../mobile/components/PhoneInput.vue';

  export default {
    components: {
      CountrySelect, PhoneInput
    },
    data () {
      return {
        Bus: Bus
      }
    },
    methods: {
      ...mapActions('Header', ['register']),
      ...mapMutations('Header/Appearance', ['hide', 'toLogin']),
      ...mapMutations('Header/Register',['setName', 'setEmail', 'setPhone', 'setPassword', 'setPasswordConfirmation', 'setAcceptance'])
    },
    computed: {
      ...mapState('Header', ['mobile']),
      ...mapState('Header/Register', ['name','email','password','password_confirmation', 'acceptance', 'errors'])
    }
  }
</script>