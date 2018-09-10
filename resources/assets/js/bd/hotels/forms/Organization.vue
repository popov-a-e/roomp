<template>
  <form class="organization" @submit.prevent="save">
    <h3>Организация</h3>

    <div class="selector">
      <button @click="showOrganizationSelector">Выбрать существующую</button>
    </div>

    <form-selector></form-selector>

    <v-input-cancelable
      :placeholder="'Название'"
      :value="name"
      :highlighted="highlighted.organization.name"
      :disabled="!canChangeOrganization"
      @input="setName"></v-input-cancelable>

    <ceo-input v-if="form !== 'ИП'"></ceo-input>

    <v-input-cancelable
      :placeholder="'ИНН'"
      :value="INN"
      :highlighted="highlighted.organization.INN"
      :disabled="!canChangeOrganization"
      @input="setINN"></v-input-cancelable>

    <v-input-cancelable
      v-if="form !== 'ИП'"
      :placeholder="'КПП'"
      :value="KPP"
      :highlighted="highlighted.organization.KPP"
      :disabled="!canChangeOrganization"
      @input="setKPP"></v-input-cancelable>

    <v-input-cancelable
      :placeholder="form === 'ИП'  ? 'ОГРНИП' : 'ОГРН'"
      :value="OGRN"
      :highlighted="highlighted.organization.OGRN"
      :disabled="!canChangeOrganization"
      @input="setOGRN"></v-input-cancelable>

    <v-input-cancelable
      v-if="form !== 'ИП'"
      :placeholder="'ОКПО'"
      :value="OKPO"
      :highlighted="highlighted.organization.OKPO"
      :disabled="!canChangeOrganization"
      @input="setOKPO"></v-input-cancelable>

    <v-input-cancelable
      :placeholder="'Р/с'"
      :value="account"
      :highlighted="highlighted.organization.account"
      :disabled="!canChangeOrganization"
      @input="setAccount"></v-input-cancelable>

    <v-input-cancelable
      :placeholder="'БИК'"
      :value="BIC"
      :highlighted="highlighted.organization.BIC"
      :disabled="!canChangeOrganization"
      @input="setBIC"></v-input-cancelable>

    <v-input-cancelable
      :placeholder="'Банк'"
      :value="bank"
      :highlighted="highlighted.organization.bank"
      :disabled="!canChangeOrganization"
      @input="setBank"></v-input-cancelable>

    <v-input-cancelable
      :placeholder="'К/с'"
      :value="corr_account"
      :highlighted="highlighted.organization.corr_account"
      :disabled="!canChangeOrganization"
      @input="setCorrAccount"></v-input-cancelable>

    <v-input-cancelable
      :placeholder="'Фактический адрес'"
      :value="fact_address"
      :highlighted="highlighted.organization.fact_address"
      :disabled="!canChangeOrganization"
      @input="setFactAddress"></v-input-cancelable>

    <v-input-cancelable
      :placeholder="'Юр. адрес'"
      :value="legal_address"
      :highlighted="highlighted.organization.legal_address"
      :disabled="!canChangeOrganization"
      @input="setLegalAddress"></v-input-cancelable>

    <v-input-cancelable
      :placeholder="'Почтовый адрес'"
      :value="post_address"
      :highlighted="highlighted.organization.post_address"
      :disabled="!canChangeOrganization"
      @input="setPostAddress"></v-input-cancelable>

    <v-input-cancelable
      :placeholder="'Телефон'"
      :value="phone"
      :highlighted="highlighted.organization.phone"
      :disabled="!canChangeOrganization"
      @input="setPhone"></v-input-cancelable>

    <v-input-cancelable
      :placeholder="'E-mail'"
      :value="email"
      :highlighted="highlighted.organization.email"
      :disabled="!canChangeOrganization"
      @input="setEmail"></v-input-cancelable>
  </form>
</template>

<script>
  import VInputCancelable from '../../components/VInputCancelable.vue';
  import VSelect from '../../components/VSelect.vue';
  import FormSelector from './FormSelector.vue';
  import CeoInput from './CeoInput.vue';
  import {mapActions, mapMutations, mapState, mapGetters} from 'vuex';

  export default {
    components: {VInputCancelable, VSelect, CeoInput, FormSelector},
    data() {
      return {
        delayTimeoutID: false
      }
    },
    methods: {
      submit() {
        this.$emit('submit');
      },
      ...mapMutations('Hotel/Organization', [
        'setForm',
        'setName',
        'setINN',
        'setKPP',
        'setAccount',
        'setOGRN',
        'setOKPO',
        'setBank',
        'setBIC',
        'setCorrAccount',
        'setLegalAddress',
        'setFactAddress',
        'setPostAddress',
        'setPhone',
        'setEmail',
        'setHotelID',
        'init'
      ]),
      ...mapMutations('Hotel', ['showOrganizationSelector']),
      ...mapActions('Hotel/Organization', ['save', 'loadBankData', 'loadCompanyData']),
      ...mapActions('Hotel', ['deferSave'])
    },
    computed: {
      ...mapState('Hotel/Organization', [
        'form',
        'name',
        'INN',
        'KPP',
        'OGRN',
        'OKPO',
        'account',
        'bank',
        'BIC',
        'corr_account',
        'legal_address',
        'fact_address',
        'post_address',
        'phone',
        'email',
        'loading_status',
      ]),
      ...mapState('Hotel', ['hotel', 'canChangeOrganization']),
      ...mapGetters('Hotel', ['highlighted']),
      ...mapGetters('Hotel/Organization', ['loadingStatusString', 'state'])
    },
    created() {
      this.init(this.hotel.organization);

      this.$watch('state', () => this.deferSave(this.save).then(save => save()));
      this.$watch('BIC', () => {
        if (this.BIC.length === 9) {
          this.loadBankData();
        }
      });
      this.$watch('INN', () => {
        if (this.INN.length === 10 || this.INN.length === 12) {
          this.loadCompanyData();
        }
      });
    },
  }
</script>