<template>
  <div v-if="anyItemSelected">Bulk Actions</div>
</template>

<script>
export default {
  inject: ["tableData", "tableStore"],
  computed: {
    items() {
      return this.tableStore.getters("items") || [];
    },
    selectedItems() {
      this.tableData.selectedRows;
      return this.items.filter(item => this.tableData.selectedRows[item.id]);
    },
    anyItemSelected() {
      return this.selectedItems && this.selectedItems.length > 0;
    },
    isLoading() {
      return this.tableStore.getters("isLoading");
    }
  },
};
</script>

<style>
</style>