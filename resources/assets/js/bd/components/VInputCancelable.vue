<template>
  <label :class="{'v-input-cancelable': true, 'v-input': true, highlighted}">
    <span>{{placeholder}}</span>
    <input :disabled="disabled" :value="value" @input="input" :type="type"/>
    <i class="fa fa-times-circle" @click="clear"></i>
  </label>
</template>

<script>
  export default {
    props: ['value', 'placeholder', 'type', 'highlighted', 'disabled'],
    methods: {
      input(e) {
        this.$emit('input', e.target.value);
      },
      clear() {
        this.$emit('input', '');
      }
    },
    watch: {
      highlighted(value) {
        if (value === 2) {
          let viewportTop = this.$el.getBoundingClientRect().y;

          const body = document.documentElement || document.body;
          let scrollTop = body.scrollTop;

          body.scrollTop = scrollTop + viewportTop - 80 - 20;
        }
      }
    }
  }
</script>