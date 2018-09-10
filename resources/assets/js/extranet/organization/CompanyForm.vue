<template>
  <div class="company-form">
    <h3>{{__('extranet.organization.company_requisities_header')}}</h3>

    <label :class="{'v-input-editable': true}" v-for="field in companyRequisities">
      <span class="name">{{__('extranet.organization.requisites.names.'+field.type.name)}}</span>
      <span class="value">{{field.field_value}}</span>
    </label>

    <div class="account_details_feedback">{{__('extranet.organization.account_details_feedback')}}</div>
  </div>
</template>


<script>
  import { mapState, mapMutations, mapActions, mapGetters   } from 'vuex';

  export default {
    computed: {
      ...mapState('Organization', ['accountDetailsOrder']),
      ...mapGetters('Organization', ['companyRequisities'])
    },
    methods: {
      ...mapMutations('Organization', ['setCompanyFormHeight'])
    },
    updated() {
      this.setCompanyFormHeight(this.$el.getBoundingClientRect().height)
    }
  }
</script>