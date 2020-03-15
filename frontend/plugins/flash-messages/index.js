import FlashMessages from "./FlashMessages";
import { flash } from "./FlashMessages";

export default {
    install(Vue) {
        Vue.component("flash-messages", FlashMessages);
        Vue.$flash = Vue.prototype.$flash = flash;
    }
};
