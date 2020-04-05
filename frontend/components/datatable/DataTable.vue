<template>
  <div>
    <div ref="table" class="table">
      <data-table-head :resource="resource" />

      <div class="table-body-wrapper">
        <!-- <spinner :show="isLoading" /> -->
        <simplebar
          v-if="pageData"
          ref="simplebar"
          class="simplebar"
          :style="{ filter: isLoading ? 'blur(8px)' : null }"
        >
          <div class="table-body">
            <div v-if="items.length > 0">
              <data-table-row
                v-for="row in items"
                :key="row.id"
                :row="row"
                :resource="resource"
              />
            </div>
            <div v-else>There are no items</div>
          </div>
        </simplebar>
      </div>

      <paginator
        :paging-info="pagingInfo"
        :is-disabled="isLoading"
        @page-selected="handlePageSelected"
      />
    </div>
  </div>
</template>

<script>
import DataTableHead from "./DataTableHead";
import DataTableRow from "./DataTableRow";
import Paginator from "./Paginator";
import Simplebar from "simplebar-vue";
import { mapGetters } from "vuex";
import DataTableLayoutMixin from "./DataTableLayoutMixin";
import dataTableModule from "@/store/data-table";
import _ from "@/lodash";

export default {
  components: {
    DataTableHead,
    DataTableRow,
    Simplebar,
    Paginator,
  },
  mixins: [DataTableLayoutMixin],
  props: {
    resource: {
      type: Object,
      required: true,
    },
  },
  provide() {
    return {
      tableStoreNamespace: this.$dynamicModuleId(),
    };
  },
  data() {
    return {
      hiddenColumns: [],
      // expandedRows: {},
    };
  },
  computedOnSteroids() {
    return {
      ...mapGetters(this.$dynamicModuleId(), [
        "pageData",
        "isLoading",
        "items",
        "isNewPage",
      ]),
      pagingInfo() {
        return _.omit(this.pageData, ["data"]);
      },
    };
  },
  watch: {
    pageData() {
      this.$nextTick(() => {
        this.layTable();
      });
    },
    isNewPage(isNewPage) {
      if (isNewPage) {
        this.tableStore.dispatch("unselectAllRows");
        this.scrollToTop();
      }
    },
  },
  beforeCreate() {
    const url = this.$options.propsData.resource.api_urls.index;

    const module = dataTableModule(url);

    this.tableStore = this.$registerDynamicModule(module);
  },
  created() {
    // this.refresh();
  },
  destroyed() {
    this.$unregisterDynamicModule();
  },
  methods: {
    refresh() {
      return this.tableStore.dispatch("refresh");
    },
    handlePageSelected(pageNum) {
      this.tableStore.dispatch("updateFilters", {
        page: pageNum.toString(),
      });
      this.$flash("Selected: " + pageNum);
    },
    scrollToTop() {
      if (this.$refs.simplebar) {
        this.$refs.simplebar.$el.querySelector(
          ".simplebar-content-wrapper"
        ).scrollTop = 0;
      }
    },
  },
};
</script>

<style scoped lang="scss">
// .table {
// }

.table-body-wrapper {
  position: relative;
  margin-bottom: var(--sp-3);
}

.table-body {
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
