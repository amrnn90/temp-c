import Vue from 'vue';
import Vuex from 'vuex';
import VuexDynamicModule from '@/plugins/vuex-dynamic-module';

Vue.use(Vuex);
Vue.use(VuexDynamicModule);

const store = new Vuex.Store({
  state: {
    count: 0
  },
  mutations: {
    increment (state) {
      state.count++
    }
  }
})


export function dynamicModule(namespace, module) {
  if (typeof store.state[namespace] === 'undefined') {
    store.registerModule(namespace, module);
  }
  
  const scopedStore = {
    get : prop => store.getters[`${namespace}/${prop}`],
    state: prop => store.state[`${namespace}/${prop}`],
    commit: (mutation, payload) => store.commit(`${namespace}/${mutation}`, payload),
    dispatch: (action, payload) => store.dispatch(`${namespace}/${action}`, payload),
  };

  return scopedStore;
}


export default store;