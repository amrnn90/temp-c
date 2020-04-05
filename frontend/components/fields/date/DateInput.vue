<template>
  <resource-form-field
    #default="{inputProps, inputListeners, hasError}"
    :field="field"
  >
    <flat-pickr
      v-bind="inputProps"
      class="field-input"
      :class="{ 'has-error': hasError }"
      :config="config"
      v-on="inputListeners"
    ></flat-pickr>
  </resource-form-field>
</template>

<script>
import ResourceFormField from "@/components/resource-form/ResourceFormField";
import flatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.css";
import l10n from "flatpickr/dist/l10n";

export default {
  components: {
    ResourceFormField,
    flatPickr,
  },
  props: {
    field: {
      type: Object,
      required: true,
    },
  },
  computed: {
    config() {
      // return this.field.options || {};
      return { ...this.field.options, locale: l10n.ar };
    },
  },
};
</script>

<style lang="scss" scoped></style>
