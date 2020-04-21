<template>
  <resource-form-field :field="field" :on-focus="() => {}">
    <div
      style="
        padding: var(--sp-6);
        border: 3px solid var(--grey-10);
        border-radius: var(--br);
      "
    >
      <component
        :is="`${nestedField.type}Input`"
        v-for="nestedField in nestedFields"
        :key="nestedField.name"
        :field="nestedField"
      />
    </div>
  </resource-form-field>
</template>

<script>
import ResourceFormField from "@/components/resource-form/ResourceFormField";

export default {
  components: {
    ResourceFormField
  },
  props: {
    field: {
      type: Object,
      required: true
    }
  },
  computed: {
    nestedFields() {
      return this.field.fields.map(nestedField => {
        return {
          ...nestedField,
          name: `${this.field.name}.${nestedField.name}`
        };
      });
    }
  }
};
</script>

<style lang="scss" scoped></style>
