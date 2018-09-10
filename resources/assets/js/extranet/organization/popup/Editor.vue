<template>
  <form class="organization-editor" @click.stop="" @keyup.esc="cancel" @submit.prevent="save">
    <h3>{{ __(`${translationsPath}.header`, currentMap)}}</h3>
    <v-input-editable
      v-for="(value, key) in map"
      :value="value"
      :currentKey="key"
      :name="__(`${translationsPath}.names.${key}`)"
      :placeholder="__(`${translationsPath}.placeholders.${key}`)"
      @input="input"
    ></v-input-editable>

    <div class="controls">
      <button type="button" class='cancel' @click="cancel">{{__('extranet.organization.cancel')}}</button>
      <button type="submit" class='save'>{{__('extranet.organization.save')}}</button>
    </div>
  </form>
</template>

<script>
  import {mapState, mapMutations, mapActions, mapGetters} from 'vuex';
  import Bus from '../../../Bus';
  import VInputEditable from '../VInputEditable.vue';

  export default {
    components: {
      VInputEditable
    },
    computed: {
      ...mapState('Organization/Editor', ['map', 'id', 'url', 'translationsPath', 'currentMap'])
    },
    methods: {
      ...mapMutations('Organization/Editor', ['input']),
      ...mapMutations('Organization', ['cancel']),
      ...mapActions('Organization/Editor', ['save']),
    },
    created() {
      Bus.$on('keydown', e => {
        if (e.keyCode === 27) this.cancel();
      });
    }

  }
</script>