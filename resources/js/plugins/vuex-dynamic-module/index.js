const VueDynamicModule = {
  install(Vue) {
    Vue.prototype.$dynamicModuleId = function() {
      return `${this.$options.name}-${this._uid}`;
    };

    Vue.prototype.$dynamicModule = function (namespace, module) {
      const store = this.$store;

      if (!module) {
        module = namespace;
        namespace = null;
      }

      if (!namespace) {
        namespace = this.$dynamicModuleId();
      }

      if (typeof store.state[namespace] === 'undefined') {
        store.registerModule(namespace, module);
      }

      return {
        state: prop => store.state[`${namespace}/${prop}`],
        getters: prop => store.getters[`${namespace}/${prop}`],
        commit: (mutation, payload) => store.commit(`${namespace}/${mutation}`, payload),
        dispatch: (action, payload) => store.dispatch(`${namespace}/${action}`, payload),
      };     
    }
  },
};

export default VueDynamicModule;