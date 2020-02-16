<template>
  <input type="text" :value="value" @input="(value) => $emit('input', value)">
</template>

<script>
export default {
  props: ['value'],
}
</script>

<style>

</style>