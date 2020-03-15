<template>
  <div>
    <h1 class="resource-label">{{ resource.label }}</h1>

    <data-table v-if="temp" :resource="resource" />

    <!-- <button @click="temp = !temp">remove</button> -->
  </div>
</template>

<script>
import DataTable from "@/components/datatable/DataTable";

export default {
  components: {
    DataTable
  },
  props: {
    resource: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      temp: true
    };
  },
  computed: {},
  created() {},
  methods: {}
};
</script>

<style scoped lang="scss">
.resource-label {
  font-size: var(--fz-2xl);
  color: var(--grey-2);
  margin-bottom: var(--sp-6);
}
</style>
