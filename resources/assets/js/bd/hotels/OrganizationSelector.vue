<template>
  <div class="organization-selector" @click.prevent.stop="">
    <input placeholder="Введите название" v-model="name" />

    <table v-if="organizations.length > 0" cellspacing="0" cellpadding="0">
      <tr>
        <th>
          Название
        </th>
        <th>
          Ген. директор
        </th>
      </tr>
      <tr @click="selectOrganization(organization)" v-for="organization in organizations">
        <td>
          {{ organization.form }} {{ organization.name }}
        </td>
        <td>
          {{ organization.CEO_short_name }}
        </td>
      </tr>
    </table>
    <div v-else>
      Ничего не найдено
    </div>
  </div>
</template>

<script>
  import {mapActions, mapMutations, mapState, mapGetters} from 'vuex';

  export default {
    data() {
      return {}
    },
    methods: {
      ...mapMutations('Hotel', ['selectOrganization']),
      ...mapActions('Hotel/OrganizationSelector', ['load'])
    },
    computed: {
      ...mapState('Hotel/OrganizationSelector', ['organizations']),
      name: {
        get() {
          return this.$store.state.Hotel.OrganizationSelector.name;
        },
        set(value) {
          this.$store.commit('Hotel/OrganizationSelector/setName', value);
          if (value.length > 0) {
            this.load();
          } else {
            this.$store.commit('Hotel/OrganizationsSelector/setOrganizations', []);
          }
        }
      }
    }
  }
</script>