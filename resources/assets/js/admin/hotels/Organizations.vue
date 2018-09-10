<template>
  <div class='card admin-organizations' v-if='module_active' @click.prevent.stop=''>
    <h2>Организации</h2>
    <table cellspacing='0' cellpadding='0'>
      <tr>
        <th>ID</th>
        <th><filter-text :val='filters.hotels' :name='"hotels"' :sortBy='sortBy' :placeholder='"Hotels"' v-on:input='setHotelsFilter'  v-on:sort='setSort'></filter-text></th>
      </tr>
      <tr @click='rowClick(organization)' v-for='organization in organizationsFiltered'>
        <td>{{ organization.id }}</td>
        <td>{{ organization.hotels }}</td>
      </tr>
    </table>
  </div>
</template>

<script>
  import { mapState, mapActions, mapMutations, mapGetters } from 'vuex';
  import FilterText from './../components/Filters/FilterText.vue';
  import Organizations from './../store/modules/Organizations';

  export default {
    components: {
      FilterText
    },
    props: ['embedded'],
    module: {Organizations},
    computed: Organizations.mapProps(),
    methods: {
      ...Organizations.mapMethods(),
      rowClick (organization) {
        if (this.embedded) {
          this.$emit('input', organization);
        }
      },
      initModule() {
        this.load();
      }
    },
    created () {
      this.$emit('module_updated');
    }
  }
</script>