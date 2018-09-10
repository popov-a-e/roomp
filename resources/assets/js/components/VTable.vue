<template>
  <div class='v-table v-table-container'>
    <table cellspacing='0' cellpadding='0' class='v-table'>
      <tr>
        <th v-for='(field, name) in fields' :class='field.class' :style='field.style'>
          <template v-if='field.type === "ID"'>
            ID
          </template>
          <template v-else-if='field.type === "text"'>
            <filter-text :val='filters[name]' :name='name' :sortBy='sortBy' :placeholder='field.placeholder' @input='value => setFilter(name, value)' @sort='setSort'></filter-text>
          </template>
          <template v-else-if='field.type === "select"'>
            <filter-select :multiple='field.multiple' :options='field.options' :selected='filters[name]' :name='name' :sortBy='sortBy' :placeholder='field.placeholder' @input='value => setFilter(name, value)' @sort='setSort'></filter-select>
          </template>
          <template v-else>
            {{ field.placeholder || name }}
          </template>
        </th>
      </tr>
      <tr @click='click(row)' v-for='row in rowsFiltered'>
        <td v-for='(field, name) in fields' :class='field.class' :style='field.style'>
          {{ row[name] }}
        </td>
      </tr>
    </table>
  </div>
</template>

<script>
  import FilterText from '../admin/components/Filters/FilterText.vue';
  import FilterSelect from '../admin/components/Filters/FilterSelect.vue';
  import { mapState, mapActions, mapMutations, mapGetters } from 'vuex';
  import {escapeRegExp} from '../helpers';

  const getter = function() {
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
  };
  
  export default {
    components: {FilterText, FilterSelect},
    props: {
      fields: {
        type: Object,
        required: true,
      },
      rows: {
        type: Array,
        required: true
      },
      rowClick: {
        type: Function
      }
    },
    data () {
      return {
        filters: {},
        sortBy: [],
      }
    },
    computed: {
      rowsFiltered() {
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
      click(row) {
        if (this.rowClick) this.rowClick(row);
      },
      setSort(value) {this.sortBy = value;},
      setFilter(name, value) {
        Vue.set(this.filters, name, value);
      }
    }
  }
</script>