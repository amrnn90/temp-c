<template>
  <resource-form-field
    #default="{inputProps, inputListeners, hasError}"
    :field="field"
  >
    <textarea
      class="field-input"
      :class="{ 'has-error': hasError }"
      v-bind="inputProps"
      v-on="inputListeners"
    />
  </resource-form-field>
</template>

<script>
import ResourceFormField from "@/components/resource-form/ResourceFormField";
export default {
  components: { ResourceFormField },
  props: {
    field: {
      type: Object,
      required: true,
    },
  },
};
</script>

<style lang="scss" scoped>
textarea {
  min-height: var(--sp-17);
}
</style>
