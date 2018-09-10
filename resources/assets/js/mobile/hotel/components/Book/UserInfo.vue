<template>
  <div class='user-info'>
    <input :placeholder='__("header.name")' v-model='name' />
    <div class='errors' v-if='errors.name'>{{ errors.name }}</div>
    <country-select v-model='phone' :phone-init='phone' :disabled='!!user' ></country-select>
    <div class='errors' v-if='errors.phone'>{{ errors.phone }}</div>
    <input v-bind:disabled='!!user'  placeholder='E-mail' v-model='email' />
    <div class='errors' v-if='errors.email'>{{ errors.email }}</div>
    <textarea v-model='comment' :placeholder='__("hotel.comment")'></textarea>
    <label v-if='!this.user'><input type='checkbox' v-on:change='e => setAcceptance(e.target.checked)' v-model='acceptance'/> {{ __("header.i_accept") }}&nbsp;<a href='/terms'>{{ __("header.agreement")}}</a></label>
  </div>
</template>

<script>
  import {mapState, mapActions, mapMutations, mapGetters} from 'vuex';
  import CountrySelect from './../../../../components/RoompHeader/CountrySelect.vue';

  export default {
    components: {
      CountrySelect
    },
    computed: {
      ...mapState('Header/Register', ['errors']),
      user () {
        return this.$store.state.Header.user;
      },
      acceptance: {
        get () {
          return this.$store.state.Header.Register.acceptance;
        },
        set (value) {
          this.$store.commit('Header/Register/setAcceptance', value);
        }
      },
      name: {
        get () {
          if (this.user) {
            this.$store.commit('Cart/setName',this.$store.state.Header.user.name);
            return this.$store.state.Header.user.name;
          }
          else return this.$store.state.Header.Register.name;
        },
        set (value) {
          this.$store.commit('Header/Register/setName', value);
          this.$store.commit('Cart/setName', value);
        }
      },
      phone: {
        get () {
          if (this.user) return this.$store.state.Header.user.phone;
          else return this.$store.state.Header.Register.phone;
        },
        set (value) {
          this.$store.commit('Header/Register/setPhone', value);
        }
      },
      email: {
        get () {
          if (this.user) return this.$store.state.Header.user.email;
          else return this.$store.state.Header.Register.email;
        },
        set (value) {
          this.$store.commit('Header/Register/setEmail', value);
        }
      },
      comment: {
        get () {
          return this.$store.state.Cart.comment;
        },
        set (value) {
          this.$store.commit('Cart/setComment', value);
        }
      },

    },
    methods: {
      ...mapMutations('Header/Register', ['setAcceptance'])
    }
  }
</script>