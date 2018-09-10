<template>
  <div class='card requisites' v-if='module_active && organization'>
    <h2>Реквизиты отеля</h2>
    <div class="country" style="clear:both;">
      <label>
        <span>Страна организации</span>
        <select v-model="country">
          <option v-for="country in countries" :value="country.id">{{country.name}}</option>
        </select>
      </label>
      <label>
        <span>Валюта отеля</span>
        <select v-model="currency">
          <option v-for="currency in currencies" :value="currency.id">{{currency.name}}</option>
        </select>
      </label>
      <label>
        <span>Язык отельера</span>
        <select v-model="language">
          <option v-for="language in languages" :value="language.id">{{language.name}}</option>
        </select>
      </label>

      <button @click='toggleOrganizations' class='hotelier-button'>Выбрать из существующих</button>
      <div v-if='organizationsVisible' style="width: 100%;" class='admin-organizations-overlay' @click='toggleOrganizations'>
        <admin-organizations :embedded='true' @input='setExistingOrganization'></admin-organizations>
      </div>

      <div class="controls" style="padding: 10px 0;" v-if="hasLocaleChanges">
        <button class="apply" @click="saveLocaleChanges">Сохранить</button>
        <button class="cancel" @click="resetLocaleChanges">Отмена</button>
      </div>
    </div>

    <div style="clear:both;">
      <h3>Компания</h3>

      <label v-for="(value, field) in organization">
        <span>{{ __('extranet.organization.requisites.names.' + field) || field }}</span>
        <input :value="value" @input='e => setOrganizationField({field, value: e.target.value})'/>
      </label>
    </div>
    <div class='controls'>
      <button @click='apply' :class='["apply", load_status]'>{{ loadStatusString }}</button>
      <button class='cancel' @click='cancel'>Отмена</button>
    </div>
  </div>
</template>

<script>
  import Organization from '../store/modules/Organization';
  import AdminOrganizations from './Organizations.vue';
  import {mapState, mapActions, mapMutations, mapGetters} from 'vuex';

  export default {
    data () {
      return {}
    },
    components: {AdminOrganizations},
    props: ['hotel_id', 'static'],
    module: {Organization},
    computed: {
      ...Organization.mapProps(),
      ...mapState('Meta', ['countries', 'currencies', 'languages']),
      CEO_short: {
        get() {
          return this.CEO_short_name || this.CEO_short_name_auto;
        },
        set(value) {
          this.setCEOShortName(value);
        }
      }
    },
    methods: {
      ...mapActions('Meta', ['loadAll']),
      ...Organization.mapMethods(),
      initModule() {
        this.setHotelID(this.hotel_id);
        this.loadAll().then(this.load);
      },
    },
    created() {
      if (this.static) {
        this.$emit('module_updated');
      }
    }
  }
</script>