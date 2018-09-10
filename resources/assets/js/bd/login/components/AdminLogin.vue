<template>
  <div class='window'>
    <img src='/img/logo.png' height='30' />
    <h1>Панель менеджера по развитию</h1>
    <input placeholder='E-mail' type='email' @input='e => setEmail(e.target.value)' :value='email' />
    <input placeholder='Пароль' type='password' @input='e => setPassword(e.target.value)' :value='password' />
    <button v-on:click='login'>Войти</button>
  </div>
</template>

<script>
  import { mapState, mapActions, mapMutations } from 'vuex';

  export default {
    computed: mapState(['email', 'password']),
    methods: {
      ...mapMutations(['setEmail', 'setPassword']),
      ...mapActions(['login'])
    },
  }
</script>


