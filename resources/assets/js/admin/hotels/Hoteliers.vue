<template>
  <div class='card admin-hoteliers' v-if='module_active' @click.prevent.stop=''>
    <h2>Отельеры</h2>
    <table cellspacing='0' cellpadding='0'>
      <tr>
        <th>ID</th>
        <th><filter-text :val='filters.email' :name='"email"' :sortBy='sortBy' :placeholder='"E-mail"' v-on:input='setEmailFilter'  v-on:sort='setSort'></filter-text></th>
        <th><filter-text :val='filters.hotels' :name='"hotels"' :sortBy='sortBy' :placeholder='"Hotels"' v-on:input='setHotelsFilter'  v-on:sort='setSort'></filter-text></th>
      </tr>
      <tr @click='rowClick(hotelier)' v-for='hotelier in hoteliersFiltered'>
        <td>{{ hotelier.id }}</td>
        <td>{{ hotelier.email }}</td>
        <td>{{ hotelier.hotels }}</td>
      </tr>
    </table>
  </div>
</template>

<script>
  import { mapState, mapActions, mapMutations, mapGetters } from 'vuex';
  import FilterText from './../components/Filters/FilterText.vue';
  import Hoteliers from './../store/modules/Hoteliers';

  export default {
    components: {
      FilterText
    },
    props: ['embedded'],
    module: {Hoteliers},
    computed: Hoteliers.mapProps(),
    methods: {
      ...Hoteliers.mapMethods(),
      rowClick (hotelier) {
        if (this.embedded) {
          this.$emit('input', hotelier);
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