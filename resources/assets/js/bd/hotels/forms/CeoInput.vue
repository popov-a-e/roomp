<template>
  <label :class="{'v-input-cancelable': 1, 'ceo-input': 1, 'v-input': 1, highlighted}" >
    <span>Генеральный директор</span>
    <input :value="CEO" @input="inputCEO" :disabled="!canChangeOrganization"/>
    <i class="fa fa-times-circle" @click.prevent.stop="clearCEO"></i>
    <input :value="CEO_short" @input="inputCEOShortName" :disabled="!canChangeOrganization"/>
    <i class="fa fa-times-circle" @click.prevent.stop="clearCEOShortName"></i>
  </label>
</template>

<script>
  import {mapState, mapGetters, mapMutations} from 'vuex';

  export default {
    computed: {
      ...mapState('Hotel/Organization', ['CEO', 'CEO_short_name']),
      ...mapGetters('Hotel/Organization', ['CEO_short_name_auto']),
      CEO_short () {
        return this.CEO_short_name || this.CEO_short_name_auto;
      },
      highlighted () {
        return this.$store.getters['Hotel/highlighted'].organization.CEO;
      },
      ...mapState('Hotel', ['canChangeOrganization'])
    },
    methods: {
      ...mapMutations('Hotel/Organization', ['setCEO', 'setCEOShortName']),

      inputCEO(e) {this.setCEO(e.target.value)},
      inputCEOShortName(e) {this.setCEOShortName(e.target.value)},
      clearCEO() {this.setCEO('')},
      clearCEOShortName() {this.setCEOShortName('')},
    },
  }
</script>