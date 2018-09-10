<template>
  <footer>
    <div class="up">
      <div>Чат с менеджером</div>
      <div>Обратная связь</div>
      <select v-model="selected">

        <option>{{selected}}</option>

      </select>
    </div>
    <div class="down">Roomp LLC, 2018</div>
  </footer>
</template>

<script>

  import { mapState, mapActions, mapMutations, mapGetters } from 'vuex';

  export default {
    data () {
      return {
        selected: 'Русский язык'
      }
    }

  }
</script>