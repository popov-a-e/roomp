<template>
  <div class='window'>
    <img src='/img/logo.png' height='30' />
      <h1>Регистрация отельера</h1>
      <input placeholder='E-mail' type='email' name="email" :value='email' @input="e => setEmail(e.target.value)" />
      <div class="errors">{{errors.email}}</div>
      <input placeholder='Пароль' type='password' name="password" :value="password" @input="e => setPassword(e.target.value)" />
      <input placeholder='Повторите пароль' type='password' name="password" :value='password_confirmation' @input="e => setPasswordConfirmation(e.target.value)" />
      <div class="errors">{{errors.password}}</div>
      <button @click='register'>Зарегистрироваться</button>
  </div>
</template>

<script>
  import { mapState, mapActions, mapMutations } from 'vuex';

  export default {
    props: ['token', 'receptionEmail'],
    computed: mapState(['email', 'password', 'password_confirmation', 'errors']),
    methods: {
      ...mapMutations(['setEmail', 'setPassword', 'setPasswordConfirmation', 'setToken']),
      ...mapActions(['register']),

    },
    created() {
      this.setToken(this.token);
      this.setEmail(this.receptionEmail);
    }
  }
</script>