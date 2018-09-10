"use strict";

export default {
  data () {
    return {
      route: null,
      active: false
    }
  },
  beforeRouteEnter (to, from, next) {
    next(vm => vm.route = to.path);
  },
  beforeRouteUpdate(to, from, next) {
    this.route = to.path;
    next();
  },
  computed: {
    moduleID () {
      return this.moduleName + '#' + this.route;
    }
  },
  watch: {
    moduleID (value) {
      if (!this.$store.state[value]) {
        this.$store.registerModule(value, this.module);
        this.initModule();
        this.active = true;
        this.$emit('activated');
      }
    }
  },
  created () {
    this.$on('update', e => this.route = this.$route.path);
  }
};