<template>
  <header>
    <div class="logo">
      <div class="container">
        <div class="circle-top"></div>
        <div class="circle-bot"></div>
      </div>
    </div>
    <nav>
      <router-link :to="'/onboarding'">
        Пайплайн
      </router-link>
      <a class="disabled">Статистика</a>
    </nav>

    <nav class="user">
      <div class="avatar"><i class="fa fa-user-circle-o"></i></div>
      <a href="javascript:void(0);">Профиль</a>
      <a href='/logout'>Выйти</a>
    </nav>
  </header>
</template>

<script>
  import {mapState, mapActions, mapMutations, mapGetters} from 'vuex';

  export default {
    props: ['user'],
    methods: {
      //...mapMutations('Header', ['initialize'])
    },
    created () {
      //this.initialize(this.user);
    }
  }

</script>