<template>
  <app-card class="table-head">
    <div class="table-head-upper">
      <div class="search-input-wrapper">
        <input
          v-model="searchFilter"
          class="search-input"
          type="search"
          placeholder="Search"
        />
        <button
          v-tooltip="'refresh'"
          style="margin-left: var(--sp-4);"
          @click="tableStore.dispatch('refresh')"
        >
          <app-icon name="rotate-cw" />
        </button>

        <app-icon name="search" stroke-width="1" class="search-input-icon" />
      </div>

      <router-link :to="{ name: `${resource.name}.create` }" class="create-btn"
        >Create New</router-link
      >
    </div>
    <div class="table-head-lower">
      <div class="table-head-pre">
        <checkbox-input
          :value="allRowsAreSelected"
          @input="toggleSelectAllRows"
        />
      </div>
      <div class="table-head-cells table-cells">
        <div
          v-for="field in resource.fields"
          :key="field.name"
          class="table-head-cell"
          :class="`cell-${field.name}`"
          style="flex-grow: 1;"
          :style="{
            paddingLeft: resource.title_field == field.name ? '60px' : '0'
          }"
        >
          <div class="table-head-cell-content">
            <strong>{{ field.label }}</strong>
            <span class="table-head-cell-sort-icons">
              <app-icon
                name="chevron-up"
                size="10"
                stroke-width="2"
                stroke="var(--grey-8)"
                style=""
              />
              <app-icon
                name="chevron-down"
                size="10"
                stroke-width="2"
                stroke="var(--grey-6)"
                style=""
              />
            </span>
          </div>
        </div>
      </div>
      <div class="table-head-post">
        <bulk-actions />
      </div>
    </div>
  </app-card>
</template>

<script>
import CheckboxInput from "@/components/inputs/CheckboxInput";
import BulkActions from "./BulkActions";

export default {
  components: {
    CheckboxInput,
    BulkActions
  },
  props: {
    resource: {
      type: Object,
      required: true
    }
  },
  inject: ["tableStoreNamespace"],
  computed: {
    tableStore() {
      return this.$dynamicModuleStore(this.tableStoreNamespace);
    },
    allRowsAreSelected() {
      return this.tableStore.getters("allRowsAreSelected");
    },
    searchFilter: {
      get() {
        return this.tableStore.getters("filters").search;
      },
      set(val) {
        this.tableStore.dispatch("updateFilters", { search: val });
      }
    }
  },
  methods: {
    toggleSelectAllRows() {
      this.tableStore.dispatch("toggleSelectAllRows");
    }
  }
};
</script>

<style scoped lang="scss">
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
  flex-shrink: 0;
}

.table-head-post {
  width: var(--table-row-post-width);
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-left: auto;
  flex-shrink: 0;
}

.table-head-cells {
  flex-grow: 1;
  display: flex;
  align-items: center;
  margin: 0 var(--table-row-cells-horizontal-margin);
}

.table-head-cell-content {
  display: flex;
  align-items: center;
}

.table-head-cell-sort-icons {
  margin-left: 10px;
  display: flex;
  flex-direction: column;

  svg {
    &:first-child {
      margin-bottom: -2px;
    }
  }
}
</style>
