<template>
  <div class='card admin-hoteliers' v-if='module_active' @click.prevent.stop=''>
    <h2>Channel Managers</h2>
    <table cellspacing='0' cellpadding='0'>
      <tr>
        <th style='width:100px; min-width: 100px; max-width: 100px;'><filter-text :val='filters.id' :name='"id"' :sortBy='sortBy' :placeholder='"#"' v-on:input='' v-on:sort='setSort'></filter-text></th>
        <th><filter-text :val='filters.name' :name='"name"' :sortBy='sortBy' :placeholder='"Имя"' v-on:input='setNameFilter'  v-on:sort='setSort'></filter-text></th>
        <th><filter-text :val='filters.login' :name='"login"' :sortBy='sortBy' :placeholder='"Логин"' v-on:input='setLoginFilter'  v-on:sort='setSort'></filter-text></th>
        </tr>
      <tr @click='rowClick(channel)' v-for='channel in channelsFiltered'>
        <td style='width:100px; min-width: 100px; max-width: 100px;'>{{ channel.id }}</td>
        <td>{{ channel.name }}</td>
        <td>{{ channel.login }}</td>
      </tr>
    </table>
  </div>
</template>

<script>
  import FilterText from './../components/Filters/FilterText.vue';
  import APIChannels from '../store/modules/APIChannels';

  export default {
    components: {FilterText},
    props: ['embedded'],
    module: {APIChannels},
    computed: APIChannels.mapProps(),
    methods: {
      ...APIChannels.mapMethods(),
      rowClick (channel) {
        if (this.embedded) {
          this.$emit('input', channel);
        }
      },
      initModule () {
        this.load()
      }
    },
    created () {
      this.$emit('module_updated');
    }
  }
</script>