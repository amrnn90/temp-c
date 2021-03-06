import Vue from "vue";
import CompositionApi from "@vue/composition-api";
import PortalVue from "portal-vue";
import VTooltip from "v-tooltip";
import Sticky from "vue-sticky-directive";
import FlashMessages from "@/plugins/flash-messages";
import ComputedOnSteroids from "@/plugins/computed-on-steroids";
import router from "@/router";
import store from "@/store";
import axios from "@/axios";
import _ from "@/lodash";
import App from "@/App";
import "@/assets/styles/app.scss";

Vue.config.productionTip = false;
Vue.use(CompositionApi);
Vue.use(PortalVue);
Vue.use(VTooltip);
Vue.use(FlashMessages);
Vue.use(ComputedOnSteroids);
Vue.use(Sticky);

window._ = _;
window.Vue = Vue;
window.axios = axios;

console.info("structure:", window.structure);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// eslint-disable-next-line no-undef
const files = require.context(
  "./",
  true,
  /(App[A-Z]\w+)|(fields\/.*Index)|(fields\/.*Input)\.vue$/i
);
files.keys().map(key =>
  Vue.component(
    key
      .split("/")
      .pop()
      .split(".")[0],
    files(key).default
  )
);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

new Vue({
  render: h => h(App),
  router,
  store
}).$mount("#app");
