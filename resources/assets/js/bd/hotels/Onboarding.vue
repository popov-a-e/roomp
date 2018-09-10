<template>
  <div class='bd'>
    <nav class="stages" v-if="!$route.params.hotel_id">
      <router-link :to="'initial'">Встреча назначена ({{initialNumber}})</router-link>
      <router-link :to="'thinking'">Думают ({{numbers.thinking}})</router-link>
      <router-link :to="'signing'">Оферта отправлена ({{signingNumber}})</router-link>
      <router-link :to="'active'">Оферта принята ({{activeNumber}})</router-link>
      <router-link :to="'deleted'">Удалены ({{numbers.deleted}})</router-link>
    </nav>

    <router-view></router-view>
  </div>
</template>

<script>
  import {mapState, mapActions, mapGetters} from 'vuex';

  export default {
    data () {
      return {}

    },
    computed: {
      ...mapState('Onboarding', ['hotels']),
      ...mapGetters('Onboarding', ['initialNumber', 'signingNumber', 'activeNumber', 'numbers']),
    },
    methods: {
      ...mapActions('Onboarding', ['load']),
      ...mapActions('Meta', ['loadAll'])
    },
    created() {
      this.loadAll().then(this.load);
    },
  }
</script>