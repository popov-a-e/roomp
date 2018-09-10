<template>
  <table cellpadding="0" cellspacing="0">
    <tr v-for="(prop, propIndex) in propsShowOrder">
      <td class="index">{{propIndex===0?index:''}}</td>
      <td class="prop">{{__('extranet.organization.contacts.names.'+prop)}}</td>
      <td class="prop-value">{{person[prop]}}</td>
      <td class="controls" v-if="propIndex === 0" rowspan="4">
        <i class="fa fa-trash-alt" @click="del"></i>
        <i class="fa fa-edit" @click="setContactData(person.id)"></i>
      </td>
    </tr>
  </table>

</template>

<script>
  import {mapState, mapMutations, mapActions, mapGetters} from 'vuex'

  export default {
    data () {
      return {
        propsShowOrder: ['name', 'position', 'email', 'phone']
      }
    },
    props: ['person', 'index'],
    methods: {
      ...mapMutations('Organization', ['setContactData']),
      ...mapActions('Organization', ['deleteContact']),
      del() {
        if (confirm(__('extranet.organization.confirm_delete'))) this.deleteContact(this.person.id);
      }
    },
  }
</script>