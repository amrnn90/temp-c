<template>
    <input type="text" :value="value" @input="(value) => $emit('input', value)" class="field-input">
</template>

<script>
export default {
  props: ['value'],
}
</script>

<style lang="scss" scoped>
</style>