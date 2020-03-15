<template>
  <div
    style="padding: var(--sp-6); border: 3px solid var(--grey-10); border-radius: var(--br)"
  >
    <resource-form-field
      v-for="field in nestedFields"
      #default="{on, props}"
      :key="field.name"
      :field="field"
    >
      <component :is="`${field.type}Input`" v-bind="props" v-on="on" />
    </resource-form-field>

    <!-- <pre>{{field}}</pre> -->
  </div>
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
  props: ["field", "value", "name", "id", "hasError"],
  computed: {
    nestedFields() {
      return this.field.fields.map(field => {
        return {
          ...field,
          name: `${this.name}.${field.name}`
        };
      });
    }
  }
};
</script>

<style lang="scss" scoped></style>
