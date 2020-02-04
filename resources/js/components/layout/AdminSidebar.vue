<template>
  <nav class="sidebar">
    <ul class="sidebar-items">
      <li class="sidebar-items__item" v-for="resource in resources" :key="resource.name">
        <router-link :to="{name: resource.name + '.index'}">{{resource.label}}</router-link>
      </li>
      <li class="sidebar-items__item">
        <a href="#">Temp one</a>
      </li>
      <li class="sidebar-items__item">
        <a href="#">Temp two</a>
      </li>
      <li class="sidebar-items__item">
        <a href="#">Temp three</a>
      </li>
    </ul>
  </nav>
</template>

<script>
export default {
  computed: {
    resources() {
      return window.structure.resources;
    }
  }
};
</script>

<style scoped lang="scss">
@import "resources/sass/init";

.sidebar {
  position: fixed;
  left: 0;
  z-index: 7777;
  width: $sidebar-width;
  height: 100vh;
  padding-top: 200px;
  background: $white;
  box-shadow: 3px 0 30px rgba(black, .05);
}

.sidebar-items {
  padding: 0 35px;
}

.sidebar-items__item {
  margin-bottom: 20px;
  font-weight: bold;
  font-size: 14px;
  color: #7e828c;

  a {
    display: block;
    padding: 13px 0;
    text-align: center;
    border-radius: 5px;
  }

  .router-link-active {
    background: $primary-light;
    color: $white;
  }
}
</style>