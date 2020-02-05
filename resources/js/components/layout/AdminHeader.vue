<template>
  <header class="header">
    <!-- Header stuff -->
  </header>
</template>

<script>
export default {};
</script>

<style scoped lang="scss">
@import "resources/sass/init";

.header {
  position: fixed;
  top: 0;
  background: white;
  width: 100%;
  height: var(--header-height);
  padding-left: calc(var(--sidebar-width) + var(--main-horizontal-padding));
  display: flex;
  align-items: center;
  z-index: 1000;
}
</style>