<template>
  <div class='card select_list' @click.stop='click'>
    <h2>Выберите элементы из списка</h2>
  
    <table cellspacing='0' cellpadding='0'>
      <tr>
        <th style='width:100px; min-width: 100px; max-width: 100px;'>ID</th>
        <th><filter-text :val='filters.name' :name='"name"' :sortBy='sortBy' :placeholder='"Название"' @input='setNameFilter'  @sort='setSort'></filter-text></th>
      </tr>
      <tr v-on:click='rowClick(row)' v-for='row in rowsFiltered' :class='{selected: isSelected(row)}'>
        <td style='width:100px; min-width: 100px; max-width: 100px;'>{{ row.id }}</td>
        <td>{{ row.name }}</td>
      </tr>
    </table>
  </div>
</template>

<script>
  import FilterText from './../components/Filters/FilterText.vue';
  import { mapthis, mapActions, mapMutations, mapGetters } from 'vuex';
  import {escapeRegExp} from '../../helpers';
  import Bus from '../../Bus';

  export default {
    components: {FilterText},
    props: ['rows', 'selected'],
    data () {
      return {
        filters: {
          name: ''
        },
        sortBy: []
      }
    },
    computed: {
      rowsFiltered () {
        let array = this.rows.filter(r => {
          let result = true;

          for (let key in this.filters) {
            if (!this.filters.hasOwnProperty(key) || !this.filters[key]) continue;

            if (this.filters[key] instanceof Array) {
              let preresult = false;

              this.filters[key].forEach(str => {
                const needle = new RegExp(escapeRegExp(str), 'i');

                preresult = preresult || !!r[key].match(needle);
              });

              if (this.filters[key].length === 0) preresult = true;

              result &= preresult;
            } else {
              console.log(key, this.filters[key], r[key]);
              const needle = new RegExp(escapeRegExp(this.filters[key]), 'i');

              result = result && r[key].match(needle);
            }
          }

          return result;
        });


        if (this.sortBy.length !== 2) return array;

        const desc = Number(this.sortBy[1] === 'desc') * 2 - 1;
        const key = this.sortBy[0];

        return array.sort((a, b) => {
          if (a[key] === b[key]) return 0;

          return a[key] > b[key] ? desc : -desc;
        });
      }
    },
    methods: {
      rowClick (row) {
        this.$emit('input', row);
      },
      setSort (value) {
        this.sortBy = value;
      },
      setNameFilter (value) {
        this.filters.name = value;
      },
      isSelected(row) {
        return this.selected.find(r => r === row.id);
      },
      click() {
        Bus.$emit('click', this._uid);
      }
    }
  }
</script>