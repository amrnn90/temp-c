<template>
  <div>
    <flat-pickr
      :value="value"
      @input="(value) => $emit('input', value)"
      :config="config"
      class="field-input"
    ></flat-pickr>

    <pre>{{field}}</pre>
  </div>
</template>

<script>
import flatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.css";
import l10n from "flatpickr/dist/l10n";

export default {
  props: ["value", "field"],
  data() {
    return {};
  },
  computed: {
    config() {
      // return this.field.options || {};
      return { ...this.field.options, locale: l10n.ar };
    }
  },
  components: {
    flatPickr
  }
};
</script>

<style lang="scss" scoped>
</style>
