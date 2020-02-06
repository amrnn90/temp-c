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
      // pageData: {},
    };
  },
  computed: {

  },
  methods: {

  },
  created() {
    // axios.get(this.resource.api_urls.index).then(({ data }) => {
    //   this.pageData = data;
    // });
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