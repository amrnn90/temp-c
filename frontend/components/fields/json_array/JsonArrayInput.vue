<template>
  <resource-form-field #default="{inputProps, inputListeners}" :field="field">
    <div style="padding: var(--sp-6); border: 1px solid var(--primary-8);">
      <div
        v-for="(nestedField, index) in nestedFields(inputProps.value)"
        :key="index"
      >
        <component
          :is="`${nestedField.type}Input`"
          :key="nestedField.name"
          :field="nestedField"
        />
        <button
          type="button"
          @click="() => remove(index, inputProps.value, inputListeners.input)"
        >
          remove
        </button>
      </div>

      <button
        type="button"
        @click="() => add(inputProps.value, inputListeners.input)"
      >
        add
      </button>

      <!-- <pre>{{field}}</pre> -->
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
  computed: {},
  methods: {
    nestedFields(value) {
      return (value || []).map((_, index) => {
        return {
          ...this.field.template_field,
          name: `${this.field.name}.${index}`
        };
      });
    },
    add(value, inputListener) {
      const newValue = [...(value || []), null];
      inputListener(newValue);
    },
    remove(index, value, inputListener) {
      const newValue = [...value.slice(0, index), ...value.slice(index + 1)];
      inputListener(newValue);
    }
  }
};
</script>

<style lang="scss" scoped></style>
