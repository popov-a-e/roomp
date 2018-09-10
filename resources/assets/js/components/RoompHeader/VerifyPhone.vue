<template>
  <div class='verify-phone' v-on:click.prevent.stop=''>
    <p>{{ __("header.confirm_phone", {phone: phone_string}) }} (<a href='javascript:void(0);' v-on:click='toRegister'>{{ __("header.change_data") }}</a>)</p>
    <input v-on:input="e => setCode(e.target.value)" v-model='code' />
    <button @click='() => register()'>{{ __("header.confirm") }}</button>
    <a href='javascript:void(0)' v-on:click='repeatSMS'>{{ __("header.repeat_sms") }}</a>
  </div>
</template>

<script>
  import { mapState, mapActions, mapMutations, mapGetters } from 'vuex';

  export default {
    computed: {
      ...mapState('Header/Register', ['phone_string']),
      code: {
        get () {
          return this.$store.state.Header.Register.code;
        },
        set(value) {
          this.setCode(value);
        }
      }
    },
    methods: {
      ...mapMutations('Header/Appearance', {toRegOriginal: 'toRegister'}),
      ...mapMutations('Header/Register', ['setCode']),
      toRegister () {
        if (this.$store.state.promise) {
          this.$store.commit('Header/Appearance/justHide');
          this.$store.commit('Header/Appearance/hideMenu');
        } else {
          this.toRegOriginal();
        }
      },
      ...mapActions('Header', ['register', 'repeatSMS'])
    }
  }
</script>