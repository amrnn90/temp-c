<template>
  <resource-form-field
    #default="{inputProps, inputListeners, hasError}"
    :field="field"
  >
    <image-upload
      :upload-url="field.options.upload_url"
      :multiple="field.options.multiple"
      :input-class="`field-input ${hasError ? 'has-error' : ''}`"
      v-bind="inputProps"
      v-on="inputListeners"
    />
  </resource-form-field>
</template>

<script>
import ResourceFormField from "@/components/resource-form/ResourceFormField";
import ImageUpload from "@/components/inputs/ImageUpload";

export default {
  components: {
    ResourceFormField,
    ImageUpload,
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
