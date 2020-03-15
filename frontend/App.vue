<template>
  <div>
    <TheHeader />

    <TheSidebar />

    <main class="main">
      <router-view />
    </main>

    <portal-target name="page-backdrop" />

    <flash-messages />

    <IconSprite />
  </div>
</template>

<script>
import TheHeader from "@/components/layout/TheHeader";
import TheSidebar from "@/components/layout/TheSidebar";

export default {
  components: {
    IconSprite: () =>
      import(/* webpackChunkName: "icon-sprite" */ "@/components/IconSprite"),
    TheHeader,
    TheSidebar
  },
  data() {
    return {};
  },
  mounted() {}
};
</script>

<style scoped lang="scss">
/* @import "resources/sass/init"; */

.main {
  padding-top: calc(var(--header-height) + var(--main-top-padding));
  padding-left: calc(var(--sidebar-width) + var(--main-horizontal-padding));
  padding-right: var(--main-horizontal-padding);
}
</style>
