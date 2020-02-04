<template>
  <div class="table-row">
    <div class="table-row-upper">
      <div class="table-row-pre">
        <checkbox-input />
      </div>
      <div class="table-row-cells table-cells">
        <div
          class="table-row-cell"
          :class="`cell-${field.name}`"
          style="flex-grow: 1"
          v-for="field in resource.fields"
          :key="field.name"
          @click="isExpanded = !isExpanded"
        >
          <component
            :is="`${field.type}Index`"
            :field="row.fields.find(f => f.name == field.name)"
            :item="row"
            :is_title_field="resource.title_field == field.name"
          />
        </div>
      </div>
      <div class="table-row-post">
        <i class="icon lni-eye"></i>
        <i class="icon lni-pencil"></i>
        <row-delete-button :item="row" />
      </div>
    </div>
    <div class="table-row-expand">
      <expand :show="isExpanded">hello</expand>
    </div>
  </div>
</template>

<script>
export default {
  props: ["row", "resource"],
  data() {
    return {
      isExpanded: false
    };
  }
};
</script>

<style scoped lang="scss">
@import "resources/sass/init";
.table-row {
  background: $white;
  padding: 13px $table-row-padding-x;
  border-radius: 5px;
  box-shadow: 1px 2px 2px rgba(black, 0.08);
  &:not(:last-child) {
    margin-bottom: 13px;
  }
}

.table-row-upper {
  display: flex;
  align-items: center;
}

.table-row-pre {
  width: $table-row-pre-width;

  display: flex;
  justify-content: center;
  align-items: center;
}

.table-row-post {
  width: $table-row-post-width;
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-left: auto;
}

.table-row-cells {
  flex-grow: 1;
  display: flex;
  align-items: center;
  cursor: pointer;
  margin: 0 $table-row-cells-margin-x;
}

.table-row-cell {
  font-size: 14px;
  color: $grey-4;
}

.icon {
  font-size: 22px;
  color: $grey-4;

  &.is-disabled {
      opacity: .3;
  }
}

.icon.lni-eye {
    font-size: 24px;
}
</style>