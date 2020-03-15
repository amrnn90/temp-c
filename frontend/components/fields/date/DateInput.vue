<template>
  <div>
    <flat-pickr
      :id="id"
      :value="value"
      :name="name"
      class="field-input"
      :class="{ 'has-error': hasError }"
      :config="config"
      @input="value => $emit('input', value)"
    ></flat-pickr>
  </div>
</template>

<script>
import flatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.css";
import l10n from "flatpickr/dist/l10n";

export default {
  components: {
    flatPickr
  },
  props: ["field", "value", "name", "id", "hasError"],
  data() {
    return {};
  },
  computed: {
    config() {
      // return this.field.options || {};
      return { ...this.field.options, locale: l10n.ar };
    }
  }
};
</script>

<style lang="scss" scoped></style>
