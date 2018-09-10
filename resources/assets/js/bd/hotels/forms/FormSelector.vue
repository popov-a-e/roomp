<template>
  <div class="v-input form-selector">
    <span>Орг. форма</span>
    <div>
      <c-button v-for="name in ['ООО','АО','ИП']"
        :name="name"
        :key="name"
        :id="name"
        :value="form === name"
        :disabled="!canChangeOrganization"
        @input="setForm(name)"
      >
      </c-button>
    </div>
  </div>
</template>

<script>
  import CButton from '../../../components/Cbutton.vue';
  import {mapActions, mapMutations, mapState, mapGetters} from 'vuex';

  export default {
    components: {CButton},
    methods: mapMutations('Hotel/Organization', ['setForm']),
    computed: {
      ...mapState('Hotel/Organization', ['form']),
      ...mapState('Hotel', ['canChangeOrganization'])
    },
  }
</script>