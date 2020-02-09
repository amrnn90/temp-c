<template>
  <div>
    <div class="table" ref="table">
      <data-table-head
        :resource="resource"
        :allRowsAreSelected="allRowsAreSelected"
        @selectAllRows="handleSelectAllRows"
      />

      <div class="table-body-wrapper">
        <!-- <spinner :show="isLoading" /> -->
        <simplebar
          v-if="pageData"
          class="simplebar"
          :style="{filter: isLoading ? 'blur(8px)' : null}"
          ref="simplebar"
        >
          <div class="table-body">
            <DataTableRow
              v-for="row in items"
              :key="row.id"
              :row="row"
              :resource="resource"
              :isSelected="!!selectedRows[row.id]"
              @select="(val) => handleRowSelectChange(row.id, val)"
            />
          </div>
        </simplebar>
      </div>

      <paginator
        :pagingInfo="pagingInfo"
        @page-selected="handlePageSelected"
        :isDisabled="isLoading"
      />
    </div>
  </div>
</template>

<script>
import simplebar from "simplebar-vue";
import { mapGetters } from "vuex";
import DataTableLayoutMixin from "./DataTableLayoutMixin";
import { objectToQuerystring } from "@/utils";
import paginationModule from "@/store/pagination";


export default {
  components: {
    simplebar
  },
  mixins: [
    DataTableLayoutMixin
  ],
  props: {
    resource: {},
    storeId: {}
  },
  provide() {
    return {
      refreshTable: this.refresh,
      tableData: this.$data,
      tableStoreNamespace: this.$dynamicModuleId()
    };
  },
  data() {
    return {
      hiddenColumns: [],
      // expandedRows: {},
      selectedRows: {},
      allRowsAreSelected: false
    };
  },
  computedOnSteroids() {
    return {
      pagingInfo() {
        return _.omit(this.pageData, ["data"]);
      },
      selectedItems() {
        return this.items.filter(item => this.selectedRows[item.id]);
      },
      ...mapGetters(this.$dynamicModuleId(), ["pageData", "isLoading", "items"])
    };
  },
  watch: {
    pageData() {
      this.$nextTick(() => {
        this.layTable();
      });
    }
  },
  methods: {
    refresh(clearRowsState = true) {
      return this.tableStore.dispatch("refresh").then(() => {
        if (clearRowsState) {
          this.allRowsAreSelected = false;
          this.selectedRows = {};
        }
      });
    },

    handlePageSelected(pageNum) {
      this.tableStore.dispatch("updateFilters", {
        page: pageNum.toString()
      });
      this.$flash("Selected: " + pageNum);
    },

    handleRowSelectChange(id, val) {
      this.selectedRows = {
        ...this.selectedRows,
        [id]: val
      };
    },
    handleSelectAllRows(val) {
      this.allRowsAreSelected = val;
      this.selectedRows = {};
      if (val) {
        this.items.forEach(row => {
          this.selectedRows[row.id] = true;
        });
      }
    },
    scrollToTop() {
      if (this.$refs.simplebar) {
        this.$refs.simplebar.$el.querySelector(
          ".simplebar-content-wrapper"
        ).scrollTop = 0;
      }
    }
  },
  beforeCreate() {
    const url = this.$options.propsData.resource.api_urls.index;

    const module = paginationModule(url, {
      syncFiltersWithRouteParams: true,
      routeParamsPrefix: "table",
      filters: {}
    });

    this.tableStore = this.$registerDynamicModule(module);
  },
  created() {
    this.refresh();
  },
  destroyed() {
    this.$unregisterDynamicModule();
  }
};
</script>

<style scoped lang="scss">
@import "resources/sass/init";

.table {
  // margin: 0 calc(var(--table-row-horizontal-padding) * -1);
}

.table-body-wrapper {
  position: relative;
  margin-bottom: var(--sp-3);
}

.table-body {
  // padding-bottom: 14px;

  /* offset to make simplebar scroller outside */
  padding: 0 calc(var(--page-card-horizontal-padding) + var(--sp-4));
}

.simplebar {
  // max-height: 470px;
  height: 400px;
  transition: filter 0.3s ease;
  overflow-x: hidden;

  margin: 0 calc(-1 * (var(--page-card-horizontal-padding) + var(--sp-4)));
}
</style>