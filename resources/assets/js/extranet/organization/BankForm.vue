<template>
  <div class="bank-form">
    <h3>{{__('extranet.organization.requisites.header')}}
      <i class="fa fa-edit" @click="setBankData"></i>
    </h3>

    <label :class="{'v-input-editable': true}" v-for="field in bankRequisities">
      <span class="name">{{__('extranet.organization.requisites.names.'+field.type.name)}}</span>
      <span class="value">{{field.field_value}}</span>
    </label>
  </div>
</template>

<script>
  import { mapState, mapMutations, mapActions, mapGetters   } from 'vuex'

  export default {
    computed: {
      ...mapGetters('Organization', ['bankRequisities']),
    },
    methods: {
      ...mapMutations('Organization', ['setBankData', 'setBankFormHeight']),
    },
    updated() {
      this.setBankFormHeight(this.$el.getBoundingClientRect().height)
    }
  }
</script>