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
              v-for="(row, rowIndex) in items"
              :key="rowIndex"
              :row="row"
              :resource="resource"
              :isSelected="!!selectedRows[rowIndex]"
              @select="(val) => handleRowSelectChange(rowIndex, val)"
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
import DataTableLayoutMixin from "./DataTableLayoutMixin";
import QueryFiltersSyncMixin from "./QueryFiltersSyncMixin";
import { objectToQuerystring } from "@/utils";

export default {
  components: {
    simplebar
  },
  mixins: [DataTableLayoutMixin, QueryFiltersSyncMixin],
  props: ["resource"],
  data() {
    return {
      pageData: null,
      filters: {
        page: null
      },
      hiddenColumns: [],
      // expandedRows: {},
      selectedRows: {},
      allRowsAreSelected: false,
      isLoading: false
    };
  },
  provide() {
    return {
      refreshTable: this.refresh,
      tableData: this.$data,
    };
  },
  computed: {
    pagingInfo() {
      return _.omit(this.pageData, ["data"]);
    },
    items() {
      return _.get(this.pageData, 'data', []);
    },
    selectedItems() {
      return this.items.filter((item, index) => this.selectedRows[index]);
    }
  },
  watch: {
    pageData() {
      this.$nextTick(() => {
        this.layTable();
      });
    },
    filters: {
      immediate: true,
      handler(newFilters, oldFilters) {
        if (_.isEqual(newFilters, oldFilters)) return;

        this.refresh().then(() => {
          this.scrollToTop();
        });
      }
    }
  },
  methods: {
    refresh() {
      this.isLoading = true;
      return axios
        .get(this.resource.api_urls.index, { params: this.activeFilters })
        .then(({ data }) => {
          this.pageData = data;

          this.allRowsAreSelected = false;
          this.selectedRows = {};
        })
        .finally(() => {
          this.isLoading = false;
        });
    },
    handlePageSelected(pageNum) {
      this.filters = {
        ...this.filters,
        page: pageNum.toString()
      };
      this.$flash("Selected: " + pageNum);
    },

    handleRowSelectChange(rowIndex, val) {
      this.selectedRows = {
        ...this.selectedRows,
        [rowIndex]: val
      };
    },
    handleSelectAllRows(val) {
      this.allRowsAreSelected = val;
      this.selectedRows = {};
      if (val) {
        this.items.forEach((row, index) => {
          this.selectedRows[index] = true;
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
  created() {
    // this.refresh();
  }
};
</script>

<style scoped lang="scss">
@import "resources/sass/init";

.table {
  margin: 0 calc(var(--table-row-horizontal-padding) * -1);
}

.table-body-wrapper {
  position: relative;
  margin-bottom: var(--sp-3);
}

.table-body {
  // padding-bottom: 14px;

  /* offset to make simplebar scroller outside */
  padding: 0 var(--sp-4);
}

.simplebar {
  // max-height: 470px;
  height: 400px;
  transition: filter 0.3s ease;
  overflow-x: hidden;

  margin: 0 calc(-1 * var(--sp-4));
}
</style>