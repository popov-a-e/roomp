<template>
  <div class='window'>
    <img src='/img/logo.png' height='30' />
    <h1>Панель администратора</h1>
    <input placeholder='E-mail' type='email' v-on:input='e => setEmail(e.target.value)' v-model='email' />
    <input placeholder='Пароль' type='password' @keydown="keydown" @input='e => setPassword(e.target.value)' v-model='password' />
    <button v-on:click='login'>Войти</button>
  </div>
</template>

<script>
  import { mapState, mapActions, mapMutations } from 'vuex';

  export default {
    computed: mapState(['email', 'password']),
    methods: {
      ...mapMutations(['setEmail', 'setPassword']),
      ...mapActions(['login']),
      keydown(e) {
        if (e.keyCode === 13) {
          this.login();
        }
      },
    }
  }
</script>