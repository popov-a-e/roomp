<template>
  <div class="organization-page">
    <h2>{{companyName}}</h2>

    <div class="wrapper">
      <div class="company-wrapper">
        <company-form></company-form>
        <bank-form></bank-form>
      </div>
      <div class="contacts-wrapper">
        <contacts-form></contacts-form>
      </div>
    </div>
  </div>
</template>

<script>
  import {mapState, mapMutations, mapActions, mapGetters} from 'vuex'
  import CompanyForm from './CompanyForm.vue'
  import BankForm from './BankForm.vue'
  import ContactsForm from './ContactsForm.vue'

  export default {
    components: {CompanyForm, BankForm, ContactsForm},
    computed: {
      ...mapGetters('Organization', ['companyName'])
    },
    methods: {
      ...mapActions('Organization', ['load']),
    },
    created() {
      this.load()
    }
  }
</script>