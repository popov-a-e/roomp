<template>
  <div>
    <form-selector></form-selector>

    <v-input-cancelable
      :placeholder="'Название'"
      :value="name"
      :highlighted="highlighted.organization.name"
      @input="setName"></v-input-cancelable>

    <ceo-input v-if="form !== 'ИП'"></ceo-input>

    <v-input-cancelable
      :placeholder="'ИНН'"
      :value="INN"
      :highlighted="highlighted.organization.INN"
      @input="setINN"></v-input-cancelable>

    <v-input-cancelable
      v-if="form !== 'ИП'"
      :placeholder="'КПП'"
      :value="KPP"
      :highlighted="highlighted.organization.KPP"
      @input="setKPP"></v-input-cancelable>

    <v-input-cancelable
      :placeholder="form === 'ИП'  ? 'ОГРНИП' : 'ОГРН'"
      :value="OGRN"
      :highlighted="highlighted.organization.OGRN"
      @input="setOGRN"></v-input-cancelable>

    <v-input-cancelable
      v-if="form !== 'ИП'"
      :placeholder="'ОКПО'"
      :value="OKPO"
      :highlighted="highlighted.organization.OKPO"
      @input="setOKPO"></v-input-cancelable>

    <v-input-cancelable
      :placeholder="'Р/с'"
      :value="account"
      :highlighted="highlighted.organization.account"
      @input="setAccount"></v-input-cancelable>

    <v-input-cancelable
      :placeholder="'БИК'"
      :value="BIC"
      :highlighted="highlighted.organization.BIC"
      @input="setBIC"></v-input-cancelable>

    <v-input-cancelable
      :placeholder="'Банк'"
      :value="bank"
      :highlighted="highlighted.organization.bank"
      @input="setBank"></v-input-cancelable>

    <v-input-cancelable
      :placeholder="'К/с'"
      :value="corr_account"
      :highlighted="highlighted.organization.corr_account"
      @input="setCorrAccount"></v-input-cancelable>

    <v-input-cancelable
      :placeholder="'Фактический адрес'"
      :value="fact_address"
      :highlighted="highlighted.organization.fact_address"
      @input="setFactAddress"></v-input-cancelable>

    <v-input-cancelable
      :placeholder="'Юр. адрес'"
      :value="legal_address"
      :highlighted="highlighted.organization.legal_address"
      @input="setLegalAddress"></v-input-cancelable>

    <v-input-cancelable
      :placeholder="'Почтовый адрес'"
      :value="post_address"
      :highlighted="highlighted.organization.post_address"
      @input="setPostAddress"></v-input-cancelable>

    <v-input-cancelable
      :placeholder="'Телефон'"
      :value="phone"
      :highlighted="highlighted.organization.phone"
      @input="setPhone"></v-input-cancelable>

    <v-input-cancelable
      :placeholder="'E-mail'"
      :value="email"
      :highlighted="highlighted.organization.email"
      @input="setEmail"></v-input-cancelable>
  </div>
</template>

<script>
  import {mapActions, mapMutations, mapState, mapGetters} from 'vuex';
  import VInputCancelable from '../../../components/VInputCancelable.vue';
  import VSelect from '../../../components/VSelect.vue';
  import FormSelector from './../FormSelector.vue';
  import CeoInput from './../CeoInput.vue';

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
      ...mapActions('Hotel/Organization', ['save', 'loadBankData']),
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
      ...mapState('Hotel', ['hotel']),
      ...mapGetters('Hotel', ['highlighted']),
      ...mapGetters('Hotel/Organization', ['loadingStatusString', 'state'])
    },
    created() {
      this.init(this.hotel.organization);

      console.log(this.BIC);
      this.$watch('state', () => this.deferSave(this.save).then(save => save()));
    },
    watch: {
      BIC (value, prev) {
        console.log(value);
        if (value.length === 9) {
          this.loadBankData();
        }
      },
    }
  }
</script>