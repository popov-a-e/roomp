<template>
  <div class='promo-codes card' v-if='module_active'>
    <h2>Промо-коды</h2>
    <div class='selector'>
      <button @click='loadActive' :class='{selected: onlyActive}'>
        Активные
      </button>
      <button @click='loadAll' :class='{selected: !onlyActive}'>
        Все
      </button>
    </div>
    <a href='#/promo/create'>Создать</a>
  
    <table cellspacing='0' cellpadding='0'>
      <tr v-if='filters'>
        <th>ID</th>
        <th><filter-text :val='filters.code' :name='"code"' :sortBy='sortBy' :placeholder='"Код"' v-on:input='setCodeFilter'  v-on:sort='setSort'></filter-text></th>
        <th>Начало действия</th>
        <th>Окончание действия</th>
        <th>Скидка</th>
        <th>Число использований</th>
      </tr>
      <tr v-if='promo && promo.id' v-on:click='rowClick(promo.id)' v-for='promo in promoFiltered'>
        <td>{{ promo.id }}</td>
        <td>{{ promo.code || '-' }}</td>
        <td>{{ promo.from || '-' }}</td>
        <td>{{ promo.till || '-' }}</td>
        <td>{{ promo.discount }}</td>
        <td>{{ promo.times_used }}</td>
      </tr>
    </table>
  </div>
</template>

<script>
  import PromoCodes from '../store/modules/PromoCodes';
  import { mapState, mapActions, mapMutations, mapGetters } from 'vuex';
  import FilterText from '../components/Filters/FilterText.vue';

  export default {
    components: {FilterText},
    module: {PromoCodes},
    computed: {
      ...PromoCodes.mapProps(),
    },
    methods: {
      initModule() {
        this.load();
      },
      loadAll() {
        this.setOnlyActive(false);
        this.load({all: true});
      },
      loadActive() {
        this.setOnlyActive(true);
        this.load();
      },
      ...PromoCodes.mapMethods(),
    }
  }
</script>