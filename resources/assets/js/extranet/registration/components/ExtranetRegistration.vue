<template>
  <div class='registration'>
    <h1>{{__('extranet.registration.header')}}</h1>
    <div>
      <input :placeholder="__('extranet.registration.email')" type='email' name="email" :value='email' @input="e => setEmail(e.target.value)"/>
      <div class="errors">{{errors.email}}</div>
      <input :placeholder="__('extranet.registration.password')" type='password' name="password" :value="password"
             @input="e => setPassword(e.target.value)"/>
      <div class="errors"></div>
      <input :placeholder="__('extranet.registration.repeat_password')" type='password' name="password" :value='password_confirmation'
             @input="e => setPasswordConfirmation(e.target.value)"/>
      <div class="errors">{{errors.password}}</div>
      <label v-if="!offer_accepted">
        <input type="checkbox" v-model="acceptance" />
        <span  v-html="__('extranet.registration.you_accept_the_offer', {href: `/offer?hotel_id=${hotel_id}&token=${token}`})"></span>
      </label>
      <button :disabled="!acceptance || loading" :class="{loading}" @click='register'>
        <template v-if="!loading">
          {{__('extranet.registration.register')}}
        </template>
        <template v-else>
          <i class="fa fa-circle-notch fa-spin"></i>
        </template>

      </button>
    </div>
  </div>
</template>


<script>
  import {mapState, mapActions, mapMutations} from 'vuex';

  export default {
    props: ['token', 'receptionEmail', 'offer_accepted', 'hotel_id'],
    computed: {
      acceptance: {
        get() {
          return this.$store.state.acceptance;
        },
        set(value) {
          this.$store.commit('setAcceptance', value);
        }
      },
      ...mapState(['email', 'password', 'password_confirmation', 'errors', 'loading'])
    },
    methods: {
      ...mapMutations(['setEmail', 'setPassword', 'setPasswordConfirmation', 'setToken', 'setHotelID']),
      ...mapActions(['register']),

    },
    created() {
      this.$store.commit('setAcceptance', this.offer_accepted);
      this.setToken(this.token);
      this.setEmail(this.receptionEmail);
      this.setHotelID(this.hotel_id);
    }
  }
</script>