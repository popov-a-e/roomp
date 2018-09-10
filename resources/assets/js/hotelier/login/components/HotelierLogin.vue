<template>
  <div class='login'>
    <h4>Панель администратора</h4>
    <div class="item">
      <label for="email">Email</label>
      <input id="email" type='email' v-on:input='e => setEmail(e.target.value)' v-model='email' />
    </div>
    <div class="item">
      <label for="password">Пароль</label>
      <input id="password" type='password' v-on:input='e => setPassword(e.target.value)' v-model='password' />
    </div>

    <button v-on:click='login'>Войти</button>
    <div class="reset-password">Забыли пароль</div>
  </div>
</template>

<script>
  import { mapState, mapActions, mapMutations } from 'vuex';

  export default {
    computed: mapState(['email', 'password']),
    methods: {
      ...mapMutations(['setEmail', 'setPassword']),
      ...mapActions(['login'])
    }
  }
</script>