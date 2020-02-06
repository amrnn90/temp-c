<template>
  <div v-if="anyItemSelected">
    Bulk Actions
  </div>
</template>

<script>
export default {
  inject: ['tableData'],
  computed: {
    selectedItems() {
      return _.get(this.tableData, 'pageData.data', []).filter((item, index) => this.tableData.selectedRows[index]);
    },
    anyItemSelected() {
      return this.selectedItems && this.selectedItems.length > 0;
    }
  }
}
</script>

<style>

</style>