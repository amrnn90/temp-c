<template>
  <div class="checkbox-input" :class="{checked: isChecked}" @click="isChecked = !isChecked"></div>
</template>

<script>
export default {
  data() {
    return {
      isChecked: false
    }
  }
}
</script>

<style scoped lang="scss">
@import "resources/sass/init";

.checkbox-input {
  width: 21px;
  height: 21px;
  border-radius: 5px;
  border: 1px solid $grey-3;
  display: inline-block;
  // position: relative;
  cursor: pointer;
  overflow: hidden;
  background: $white;

&::after {
    content: '';
    width: 100%;
    height: 100%;
    transform: scale(0, 0);
    opacity: 0;
    background: $primary;
    border-radius: 5px;
    display: inline-block;
    transition: all .2s ease;
  }
}

.checked::after {
  opacity: 1;
  transform: scale(.6,.6);
}

</style>