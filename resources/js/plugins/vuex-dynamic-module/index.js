const VueDynamicModule = {
  install(Vue) {
    Vue.prototype.$dynamicModuleId = function () {
      return `${this.$options.name}-${this._uid}`;
    };

    Vue.prototype.$dynamicModuleStore = function (namespace) {
      const store = this.$store;
      namespace = namespace || this.$dynamicModuleId();
      return {
        state: prop => store.state[`${namespace}/${prop}`],
        getters: prop => store.getters[`${namespace}/${prop}`],
        commit: (mutation, payload) => store.commit(`${namespace}/${mutation}`, payload),
        dispatch: (action, payload) => store.dispatch(`${namespace}/${action}`, payload),
      };
    }

    Vue.prototype.$registerDynamicModule = function (namespace, module) {
      const store = this.$store;

      if (!module) {
        module = namespace;
        namespace = null;
      }

      namespace = namespace || this.$dynamicModuleId();

      if (typeof store.state[namespace] !== 'undefined') {
        console.error(`[vuex-dynamic-module] Warning: cannot register module (${namespace}) because it already exists.`)

        return this.$dynamicModuleStore(namespace);
      }

      store.registerModule(namespace, module);

      return this.$dynamicModuleStore(namespace)
    }

    Vue.prototype.$unregisterDynamicModule = function (namespace) {
      namespace = namespace || this.$dynamicModuleId();

      if (typeof this.$store.state[namespace] === 'undefined') {
        console.error(`[vuex-dynamic-module] Warning: cannot unregister module (${namespace}) because it does not exist.`)
        return;
      }

      return this.$store.unregisterModule(namespace);
    }
  },
};

export default VueDynamicModule;