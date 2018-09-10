<template>
  <div class='navigation'>
    <h2>Категория</h2>
    <nav>
      <a
        v-for='cl in classes'
        v-bind:class='{checked: classChecked.id === cl.id}'
        v-on:click='check(cl)'
      >{{ cl.name }}</a>
    </nav>
    <h3>Удобства в номере</h3>
  </div>
</template>

<script>
  import {mapState, mapGetters, mapMutations} from 'vuex';

  export default {
    data () {
      return {
        checked: false
      }
    },
    methods: mapMutations('Appearance',{
      check: 'checkClass'
    }),
    computed: {
      ...mapState({
        rooms: state => state.rooms,
        classChecked: state => state.Appearance.classChecked
      }),
      ...mapGetters({
        classes: 'classes'
      })
    }
  }
</script>