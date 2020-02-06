<template>
  <page-card class="table-head">
      <div class="table-head-upper">
        <div class="search-input-wrapper">
          <input class="search-input" type="search" placeholder="Search" />
          <icon name="search" strokeWidth="1" class="search-input-icon" />
        </div>
  
        <button class="create-btn">Create New</button>
      </div>
      <div class="table-head-lower">
        <div class="table-head-pre">
          <checkbox-input :value="allRowsAreSelected" @input="val => $emit('selectAllRows', val)" />
        </div>
        <div class="table-head-cells table-cells">
          <div
            class="table-head-cell"
            :class="`cell-${field.name}`"
            style="flex-grow: 1"
            :style="{paddingLeft: resource.title_field == field.name ? '60px' : '0'}"
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
        <div class="table-head-post">
          <bulk-actions />
        </div>
      </div>
  </page-card>
</template>

<script>
export default {
  props: ["resource", "allRowsAreSelected"]
};
</script>

<style scoped lang="scss">
@import "resources/sass/init";

.search-input-wrapper {
  position: relative;
  .search-input-icon {
    position: absolute;
    top: 50%;
    left: var(--sp-3);
    transform: translateY(-50%);
  }
}

.search-input {
  // height: var(--sp-10);
  background: var(--grey-10);
  // color: var(--primary-10);
  border-radius: var(--br);
  padding: var(--sp-3) var(--sp-7);
  padding-left: var(--sp-11);
  font-size: var(--fz-xs);
  font-weight: var(--fw-semibold);

  &::placeholder {
    color: var(--grey-7);
  }
  & + .search-input-icon {
    color: var(--grey-7);
  }

  &:focus,
  &:focus + .search-input-icon {
    color: var(--grey-4);
  }
}

.create-btn {
  // height: var(--sp-10);
  background: var(--primary);
  color: var(--primary-10);
  border-radius: var(--br);
  padding: var(--sp-3) var(--sp-7);
  font-size: var(--fz-xs);
  font-weight: var(--fw-bold);
}

.table-head {

  margin-bottom: var(--sp-3);
}

.table-head-upper {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: var(--sp-6);
}
.table-head-lower {
  display: flex;
  align-items: center;
  font-size: var(--fz-xs);
  font-weight: var(--fw-bold);
  color: var(--grey-8);
  text-transform: uppercase;
}

.table-head-pre {
  width: var(--table-row-pre-width);

  display: flex;
  justify-content: center;
  align-items: center;
}

.table-head-post {
  width: var(--table-row-post-width);
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-left: auto;
}

.table-head-cells {
  flex-grow: 1;
  display: flex;
  align-items: center;
  margin: 0 var(--table-row-cells-horizontal-margin);
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