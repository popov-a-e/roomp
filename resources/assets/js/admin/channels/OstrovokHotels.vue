<template>
  <div class="ostrovok_hotels card" @click.prevent.stop="">
    <h5>Отели в Ostrovok.ru</h5>

    <button class="refresh_data" @click="refreshOstrovok">
      <span v-if="!loading">Обновить информацию</span><span v-else>Загрузка...</span>
    </button>

    <table cellspacing='0' cellpadding='0'>
      <tr>
        <th style='width:100px; min-width: 100px; max-width: 100px;'><filter-text :val='filters.id' :name='"id"' :sortBy='sortBy' :placeholder='"#"' v-on:input='' v-on:sort='setSort'></filter-text></th>
        <th><filter-text :val='filters.name' :name='"name"' :sortBy='sortBy' :placeholder='"Название"' v-on:input='setNameFilter'  v-on:sort='setSort'></filter-text></th>
        <th><filter-text :val='filters.hotel_id' :name='"hotel_id"' :sortBy='sortBy' :placeholder='"Ассоциирован ID"' v-on:input='setHotelIdFilter'  v-on:sort='setSort'></filter-text></th>
      </tr>
      <tr @click='rowClick(hotel)' v-for='hotel in hotelsFiltered'>
        <td>{{ hotel.id }}</td>
        <td>{{ hotel.name }}</td>
        <td>{{ hotel.hotel_id}}</td>
      </tr>
    </table>
  </div>
</template>

<script>
  import {mapState, mapMutations, mapActions, mapGetters} from 'vuex';
  import FilterText from './../components/Filters/FilterText.vue';
  import OstrovokHotels from '../store/modules/OstrovokHotels';

  export default {
    components: {
      FilterText
    },
    module: {OstrovokHotels},
    methods: {
      initModule() {
        this.load();
      },
      rowClick(id) {
        this.$emit('input', id);
      },
      ...OstrovokHotels.mapMethods()
    },
    computed: {
      ...OstrovokHotels.mapProps()
    },
    created () {
      this.$emit('module_updated');
    }
  }
</script>