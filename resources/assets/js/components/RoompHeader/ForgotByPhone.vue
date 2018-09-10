<template>
  <div class='forgot-by-phone' v-on:click.stop='Bus.$emit("click",_uid)'>
    <button class='close' v-on:click='hide'><i class='icon-close'></i></button>
    <h2>{{ __("header.password_reset") }}</h2>
    <country-select v-if='!mobile' @input='setResetPhone'></country-select>
    <phone-input v-else @input='setResetPhone'></phone-input>
    <div v-if='codeSent'>
      <input v-model='reset_code' :placeholder='__("header.confirmation_code")' v-on:input='e => setResetCode(e.target.value)' />
      <button v-on:click='resetPasswordByPhone'>{{ __("header.confirm") }}</button>
    </div>
    <button v-if='codeSent' v-on:click='repeatResetSMS' class='repeat'>{{ __("header.repeat_sms") }}</button>
    <button v-on:click='resetPasswordByPhone' v-else>{{ __("header.send_sms") }}</button>
    <button class='back' v-on:click='toLogin'>{{ __("header.back") }}</button>
  </div>
</template>

<script>
  import CountrySelect from './CountrySelect.vue';
  import Bus from './../../Bus';
  import PhoneInput from '../../mobile/components/PhoneInput.vue';
  import {mapState, mapActions, mapMutations} from 'vuex';
  
  export default {
    data () {
      return {
        Bus: Bus
      }
    },
    components: {
      CountrySelect, PhoneInput
    },
    computed: {
      ...mapState('Header/Appearance',['codeSent']),
      ...mapState('Header',['reset_code', 'mobile'])
    },
    methods: {
      ...mapActions('Header', ['resetPasswordByPhone', 'repeatResetSMS']),
      ...mapMutations('Header', ['setResetCode', 'setResetPhone']),

      ...mapMutations('Header/Appearance', ['hide', 'toLogin'])
    }
  }
</script>