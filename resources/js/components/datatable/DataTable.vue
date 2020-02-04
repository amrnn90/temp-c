<template>
  <div>
    <div class="table" ref="table">
      <data-table-head :resource="resource" />

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
              v-for="(row, rowIndex) in pageData.data"
              :key="rowIndex"
              :row="row"
              :resource="resource"
            />
          </div>
        </simplebar>
      </div>
    </div>

    <paginator
      :pagingInfo="pagingInfo"
      @page-selected="handlePageSelected"
      :isDisabled="isLoading"
    />
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
      isLoading: false
    };
  },
  provide() {
    return {
      refreshTable: this.refresh
    };
  },
  computed: {
    pagingInfo() {
      return _.omit(this.pageData, ["data"]);
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
          const currentPage =
            data.current_page > data.last_page
              ? data.last_page
              : data.current_page;

          this.filters = {
            ...this.filters,
            page: currentPage.toString()
          };
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

.table-body-wrapper {
  position: relative;
}

.table-body {
  padding-bottom: 14px;
  padding-right: 15px;
}

// .table-body-wrapper::after {
//   content: '';
//   width: 100%;
//   height: 40px;
//   bottom: 0;
//   position: absolute;
//   background: linear-gradient(to top, $grey-1, transparent);
//   pointer-events: none;
// }

.simplebar {
  // max-height: 470px;
  height: 470px;
  transition: filter 0.3s ease;
  margin-right: -15px;
  overflow-x: hidden;
}
</style>