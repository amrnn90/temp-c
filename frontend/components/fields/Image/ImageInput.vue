<template>
  <div>
    <image-upload
      :id="id"
      :upload-url="field.options.upload_url"
      :multiple="field.options.multiple"
      :name="name"
      :input-class="`field-input ${hasError ? 'has-error' : ''}`"
      :value="value"
      @input="value => $emit('input', value)"
    />
  </div>
</template>

<script>
export default {
  props: ["field", "value", "name", "id", "hasError"]
};
</script>

<style lang="scss" scoped></style>
