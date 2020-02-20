<template>
  <div>
    <image-upload
    :uploadUrl="field.options.upload_url"
    :multiple="field.options.multiple"
    :name="name"
    :id="id"
    class="field-input"
    :class="{'has-error': hasError}"
    :value="value"
    @input="(value) => $emit('input', value)"
    />
  </div>
</template>

<script>
export default {
  props: ["field", "value", "name", "id", "hasError"]
};
</script>

<style lang="scss" scoped>
</style>