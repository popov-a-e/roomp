<template>
  <form class="contact-face" @submit.prevent="save">
    <h3>Основной контакт</h3>

    <v-input-cancelable :placeholder="'ФИО'" :value="name"
                        @input="setName"></v-input-cancelable>

    <v-input-cancelable :placeholder="'Должность'" :value="position"
                        @input="setPosition"></v-input-cancelable>

    <v-input-cancelable :placeholder="'E-mail'" :type="'email'" :value="email"
                        @input="setEmail"></v-input-cancelable>

    <v-input-cancelable :placeholder="'Телефон'" :value="phone"
                        @input="setPhone"></v-input-cancelable>
  </form>
</template>

<script>
  import VInputCancelable from '../../components/VInputCancelable.vue';
  import VSelect from '../../components/VSelect.vue';
  import {mapActions, mapMutations, mapState, mapGetters} from 'vuex';

  export default {
    components: {VInputCancelable, VSelect},
    data() {
      return {}
    },
    methods: {
      submit() {
        this.$emit('submit');
      },
      ...mapMutations('Hotel/ContactFace', [
        'setName',
        'setPosition',
        'setEmail',
        'setPhone',
        'setRoomNumber',
        'init', 'initEmpty'
      ]),
      ...mapActions('Hotel/ContactFace', ['save']),
      ...mapActions('Hotel', ['deferSave'])
    },
    computed: {
      ...mapState('Hotel/ContactFace', [
        'name',
        'position',
        'phone',
        'email',

        'loading_status'
      ]),
      ...mapState('Hotel', ['hotel']),
      ...mapGetters('Hotel/ContactFace', ['state'])
      /* ...mapStateDynamic('Hotel', {
       city_id: 'setCityID'
       })*/
    },
    created() {
      const contact = this.hotel.organization.contacts[0];

      if (contact) this.init(contact);
      else this.initEmpty(this.hotel.organization.id);

      this.$watch('state', () => {
        this.deferSave(this.save).then(save => save())
      });
    }
  }
</script>