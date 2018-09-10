"use strict";

const module = function(vm) {
  this.__instance = vm;
  this.__route = vm.$route ? vm.$route.path : null;
  this.__moduleName = null;

  if (vm.$options.module instanceof Object) {
    this.__moduleName = Object.keys(vm.$options.module)[0];
    this.__module = vm.$options.module[this.__moduleName];
  }
};

module.prototype.setRoute = function(path) {
  if (!this.__moduleName) return;

  const vm = this.__instance;

  this.__route = path + (vm.module_id ? '&' + vm.module_id : '');

  vm.moduleID = this.__moduleName + '#' + this.__route;

  if (!vm.$store.state[vm.moduleID]) {
    vm.$store.registerModule(vm.moduleID, this.__module.store);
    vm.initModule();
  }

  vm.module_active = true;
  vm.$emit('module_activated');
};

const Mapper = {};

Mapper.install = function (Vue, options) {
  Vue.prototype.$module = module;

  Vue.mixin({
    data () {
      return {
        module_active: false,
        moduleID: null
      }
    },
    beforeRouteEnter (to, from, next) {
      next(vm => vm.$module.setRoute(to.path));
    },
    beforeRouteUpdate(to, from, next) {
      this.$module.setRoute(to.path);
      next();
    },
    beforeCreate () {
      this.$module = new this.$module(this);
      this.$on('module_updated', e => {
        this.$module.setRoute(this.$route.path)
      });
    },
  });
};

export default Mapper;