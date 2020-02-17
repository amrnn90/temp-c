<template>
    <textarea type="text" :value="value" @input="(value) => $emit('input', value)" class="field-input" />
</template>

<script>
export default {
  props: ['value'],
}
</script>

<style lang="scss" scoped>
  textarea {
    min-height: var(--sp-17);
  }
</style>