<template>
  <div v-if="totalPages > 1">
    <div class="paginator" :class="{'is-disabled': isDisabled}">
      <i class="prev lni-chevron-left" :class="{'is-disabled': isFirstPage}" @click="handlePrev"></i>
      <span class="pages">
        <span
          v-for="pageNum in Array(totalPages).keys()"
          :key="pageNum"
          class="page"
          :class="{active: pageNum+1 == currentPage}"
          @click="handlePageSelect(pageNum+1)"
        >{{pageNum + 1}}</span>
      </span>
      <i class="next lni-chevron-right" :class="{'is-disabled': isLastPage}" @click="handleNext"></i>
    </div>
    <pre>{{pagingInfo}}</pre>
  </div>
</template>

<script>
export default {
  props: ["pagingInfo", "isDisabled"],
  computed: {
    totalPages() {
      return Math.ceil(
        _.get(this.pagingInfo, "total", 0) /
          _.get(this.pagingInfo, "per_page", 10)
      );
    },
    currentPage() {
        return this.pagingInfo.current_page;
    },
    isFirstPage() {
        return this.currentPage == 1;
    },
    isLastPage() {
        return this.currentPage == this.totalPages;
    }
  },
  methods: {
      handlePrev() {
          if (this.isFirstPage) return;
          this.handlePageSelect(this.currentPage - 1);
      },
      handleNext() {
          if (this.isLastPage) return;
          this.handlePageSelect(this.currentPage + 1);
      },
      handlePageSelect(pageNum) {
          if (pageNum == this.currentPage || this.isDisabled) return;
          this.$emit('page-selected', pageNum);
      }
  }
};
</script>

<style scoped lang="scss">
@import "resources/sass/init";

.paginator {
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 12px;
  background: $white;
  border-radius: 5px;

  &.is-disabled {
      opacity: .6;
      pointer-events: none;
  }
}

// .pages {
//   // display: inline-flex;
//   // align-items: center;
// }

.page,
.prev,
.next {
  cursor: pointer;
}
.page {
  // display: inline-block;
  line-height: 25px;
  text-align: center;
  border-radius: 5px;
  width: 25px;
  height: 25px;

  &:not(:last-child) {
    margin-right: 20px;
  }

  &:hover {
      background: $primary-light;
      color: white;
  }

  &.active {
    background: $primary;
    color: $white;
  }

}

.prev,
.next {
  font-size: 14px;
  // vertical-align: middle;
  &.is-disabled {
      cursor: default;
      color: $grey-3;
      pointer-events: none;
  }
}
.prev {
  margin-right: 20px;
}
.next {
  margin-left: 20px;
}
</style>