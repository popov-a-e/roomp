<template>
  <div class="contacts-form" :style="{maxHeight: contactsFormHeight+'px'}">
    <h3>{{__('extranet.organization.contacts_header')}}
      <i class="fa fa-plus-square" @click="setContactData(null)"></i>
    </h3>

    <contact-card
      v-for="(person, index) in contacts"
      :person="person"
      :index="index+1"
    ></contact-card>

    <div class="no-contacts" v-if="contacts.length === 0"><span>{{__('extranet.organization.no_contacts')}}</span></div>
  </div>
</template>

<script>
  import ContactCard from './ContactCard.vue';
  import {mapState, mapMutations, mapActions, mapGetters} from 'vuex';

  export default {
    components: {ContactCard},
    computed: {
      ...mapState('Organization', ['contacts']),
      ...mapGetters('Organization', ['contactsFormHeight']),
    },
    methods: {
      ...mapMutations('Organization', ['setContactData'])
    },
  }
</script>