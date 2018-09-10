<template>
  <div class='plus_selector'>
    <button @click='minus'>-</button>
      <span>{{ valueString }}</span>
    <button @click='plus'>+</button>
  </div>
</template>

<script>
  export default {
    props: ['module', 'name', 'mutation', 'max', 'min', 'plural'],
    computed: {
      state () {
        if (this.module) return this.$store.state[this.module];
        return this.$store.state;
      },
      moduleName () {
        if (this.module) return this.module + '/';
        return '';
      },
      valueString () {
        if (this.plural) return pluralize(this.plural, this.value);
        return this.value;
      },
      value: {
        get () {
          return this.state[this.name];
        },
        set (value) {
          if (!isNaN(Number(this.max))) value = Math.min(value, this.max);
          if (!isNaN(Number(this.min))) value = Math.max(value, this.min);
          
          this.$store.commit(this.moduleName + this.mutation, value);
        }
      }
    },
    methods: {
      normalize () {
        let value = this.value;
        if (!isNaN(Number(this.max))) value = Math.min(value, this.max);
        if (!isNaN(Number(this.min))) value = Math.max(value, this.min);
        this.value = value;
      },
      plus () {this.value ++},
      minus() {this.value --},
    },
    watch: {
      min () {this.normalize()},
      max () {this.normalize()}
    }
  }
</script>