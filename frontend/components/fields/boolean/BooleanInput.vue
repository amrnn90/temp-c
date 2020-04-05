<template>
  <resource-form-field
    #default="{inputProps, inputListeners, hasError}"
    :field="field"
  >
    <toggle-input
      :class="{ 'has-error': hasError }"
      v-bind="inputProps"
      v-on="inputListeners"
    />
  </resource-form-field>
</template>

<script>
import ResourceFormField from "@/components/resource-form/ResourceFormField";
import ToggleInput from "@/components/inputs/ToggleInput";
export default {
  components: {
    ResourceFormField,
    ToggleInput,
  },
  props: {
    field: {
      type: Object,
      required: true,
    },
  },
};
</script>

<style lang="scss" scoped></style>
