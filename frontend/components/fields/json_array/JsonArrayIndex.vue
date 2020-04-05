<template>
  <div style="max-width: 300px; display: flex; align-items: center;">
    <!-- <toggle-input :value="field.data" /> -->
    Temp
  </div>
</template>

<script>
export default {
  props: {
    field: {
      type: Object,
      required: true,
    },
    item: {
      type: Object,
      required: true,
    },
    isTitleField: {
      type: Boolean,
      default: false,
    },
  },
};
</script>

<style scoped lang="scss"></style>
