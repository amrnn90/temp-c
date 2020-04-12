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
import BooleanInput from "@/components/fields/boolean/BooleanInput";
import DateInput from "@/components/fields/date/DateInput";
import ImageInput from "@/components/fields/image/ImageInput";
import JsonInput from "@/components/fields/json/JsonInput";
import JsonArrayInput from "@/components/fields/json_array/JsonArrayInput";
import LongTextInput from "@/components/fields/long_text/LongTextInput";
import ShortTextInput from "@/components/fields/short_text/ShortTextInput";

export default {
  components: {
    ResourceFormField,
    BooleanInput,
    DateInput,
    ImageInput,
    JsonInput,
    JsonArrayInput,
    LongTextInput,
    ShortTextInput
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
