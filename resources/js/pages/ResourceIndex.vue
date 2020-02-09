<template>
  <div>
    <h1 class="resource-label">{{resource.label}}</h1>

    <data-table :resource="resource" />

  </div>
</template>

<script>
export default {
  props: ["resource"],
  data() { 
    return {
    };
  },
  computed: {

  },
  methods: {

  },
  created() {
  }
};
</script>


<style scoped lang="scss">
@import "resources/sass/init";

.resource-label {
  font-size: var(--fz-2xl);
  color: var(--grey-2);
  margin-bottom: var(--sp-6);
}
</style>