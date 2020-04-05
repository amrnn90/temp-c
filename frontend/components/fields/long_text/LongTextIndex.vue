<template>
  <div style="max-width: 300px; display: flex; align-items: center;">
    <img
      v-if="isTitleField"
      :src="`https://i.pravatar.cc/40?u=${field.data.split('').join('_')}`"
      alt=""
      style="
        margin-right: 24px;
        border-radius: 50%;
        flex-shrink: 0;
        width: var(--sp-9);
        height: var(--sp-9);
      "
    />

    <span class="truncate">
      {{ field.data }}
    </span>
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
