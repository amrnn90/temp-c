<template>
  <toggle-input
    :id="id"
    :name="name"
    :class="{ 'has-error': hasError }"
    :value="value"
    @input="value => $emit('input', value)"
  />
</template>

<script>
import ToggleInput from "@/components/inputs/ToggleInput";
export default {
  components: {
    ToggleInput
  },
  props: ["field", "value", "name", "id", "hasError"]
};
</script>

<style lang="scss" scoped></style>
