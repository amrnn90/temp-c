<template>
  <div class="table-head">
    <div class="table-head-pre">
      <checkbox-input />
    </div>
    <div class="table-head-cells table-cells">
      <div
        class="table-head-cell"
        :class="`cell-${field.name}`"
        style="flex-grow: 1"
        :style="{paddingLeft: resource.title_field == field.name ? '64px' : '0'}"
        v-for="field in resource.fields"
        :key="field.name"

      >
        <strong>{{field.label}}</strong>
        <span class="table-head-cell-sort-icons">
          <i class="lni-chevron-up"></i>
          <i class="lni-chevron-down"></i>
        </span>
      </div>
    </div>
    <div class="table-head-post"></div>
  </div>
</template>

<script>
export default {
  props: ["resource"]
};
</script>

<style scoped lang="scss">
@import "resources/sass/init";

.table-head {
  font-size: 14px;
  font-weight: bold;
  text-transform: uppercase;
  margin-bottom: 17px;
  display: flex;
  align-items: center;
  padding: 0 $table-row-padding-x;
}

.table-head-pre {
  width: $table-row-pre-width;

  display: flex;
  justify-content: center;
  align-items: center;
}

.table-head-post {
  width: $table-row-post-width;
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-left: auto;
}

.table-head-cells {
  flex-grow: 1;
  display: flex;
  align-items: center;
  margin: 0 $table-row-cells-margin-x;
}

.table-head-cell {
  display: flex;
  align-items: center;
}

.table-head-cell-sort-icons {
  margin-left: 10px;
  display: flex;
  flex-direction: column;

  i {
    font-size: 7px;
    font-weight: bold;

    &:first-child {
      margin-bottom: 1px;
    }
  }
}
</style>