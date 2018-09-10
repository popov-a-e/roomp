<template>
  <div class='card business_developers' v-if='module_active'>
    <h2>Business Developers</h2>
    <a href='#/bd/new'>Создать</a>
    <v-table
      :fields='fields'
      :rows='business_developers'
      :row-click='row => rowClick(row.id)'
      v-if='business_developers && business_developers.length'
    ></v-table>
  </div>
</template>

<script>
  import VTable from '../../components/VTable.vue';
  import BusinessDevelopers from '../store/modules/BusinessDevelopers';
  import { mapState, mapActions, mapMutations, mapGetters } from 'vuex';

  export default {
    components: {VTable},
    data () {
      return {
        fields: {
          name: {type: 'text', placeholder: 'ФИО'},
          phone: {type: 'text', placeholder: 'Телефон'},
          email: {type: 'text', placeholder: 'E-mail'},
          amo_id: {placeholder: 'AmoCRM ID'}
        },
      }
    },
    computed: {
      ...BusinessDevelopers.mapProps()
    },
    methods: {
      ...BusinessDevelopers.mapMethods(),
      initModule() {this.load();},
    },
    module: {BusinessDevelopers}
  }
</script>