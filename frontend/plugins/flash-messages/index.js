import FlashMessages from "./FlashMessages";
import FlashMessage from "./FlashMessage";
import { flash } from "./FlashMessages";

export default {
  install(Vue) {
    Vue.component("flash-messages", FlashMessages);
    Vue.component("flash-message", FlashMessage);
    Vue.$flash = Vue.prototype.$flash = flash;
  },
};
