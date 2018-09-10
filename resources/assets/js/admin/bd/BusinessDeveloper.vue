<template>
  <div class='card business_developer' v-if='module_active'>
    <h2>Business Developer</h2>
    <div>
      <div>
        <label>Имя</label>
        <input v-model='name' />
      </div>
      <div>
        <label>Телефон</label>
        <input v-model='phone' />
      </div>
      <div>
        <label>Email (логин)</label>
        <input v-model='email' />
      </div>
      <div>
        <label>Пароль</label>
        <input v-model='password' />
      </div>
      <div>
        <label>ID в AmoCRM</label>
        <input v-model='amo_id' />
      </div>
      
      <div v-if='changes'>
        <button @click='apply' :class="['button', 'apply', upload_status]">{{uploadStatus}}</button>
        <button class='button cancel' @click='cancel' v-if='upload_status !== "success"'>Отмена</button>
      </div>
    </div>
  </div>
</template>

<script>
  import VTable from '../../components/VTable.vue';
  import BusinessDeveloper from '../store/modules/BusinessDeveloper';
  import { mapState, mapActions, mapMutations, mapGetters } from 'vuex';

  export default {
    props: ['business_developer_id'],
    data () {
      return {
      }
    },
    computed: {
      ...BusinessDeveloper.mapProps()
    },
    methods: {
      ...BusinessDeveloper.mapMethods(),
      initModule() {
        if (this.business_developer_id) {
          this.setID(this.business_developer_id);
          this.load();
        }
      },
    },
    module: {BusinessDeveloper}
  }
</script>