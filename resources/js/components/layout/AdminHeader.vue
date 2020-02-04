<template>
  <header class="header">Header stuff</header>
</template>

<script>
export default {};
</script>

<style scoped lang="scss">
@import "resources/sass/init";

.header {
    position: fixed;
    top: 0;
    background: $white;
    width: 100%;
    height: $header-height;
    padding-left: $sidebar-width + $main-horizontal;
    display: flex;
    align-items: center;
    z-index: 1000;
    /* padding: 10px; */
}
</style>