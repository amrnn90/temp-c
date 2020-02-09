import Vue from 'vue';
import Vuex from 'vuex';
import VuexDynamicModule from '@/plugins/vuex-dynamic-module';

Vue.use(Vuex);
Vue.use(VuexDynamicModule);

const store = new Vuex.Store({
  state: {
  },
});

export default store;