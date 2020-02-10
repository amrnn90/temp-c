import paginationModule from '@/store/pagination';

export default (url) => {
  return {
    state: {
      selectedRowsIds: [],
      // scrollToTop: false,
    },
    getters: {
      selectedRowsIds(state) {
        return state.selectedRowsIds;
      },
      selectedRows(state, getters) {
        return getters.items.filter(item => state.selectedRowsIds.find(id => id == item.id));
      },
      isSelectedRow: (state) => (rowId) => {
        return !!state.selectedRowsIds.find(id => id == rowId);
      },
      allRowsAreSelected(state, getters) {
        const ids = getters.items.map(item => item.id);
        for (let rowId of ids) {
          if (!state.selectedRowsIds.find(id => id == rowId)) {
            return false;
          }
        }
        return true;
      }
    },
    mutations: {
      SELECT_ROW(state, rowId) {
        if (!!state.selectedRowsIds.find(id => id == rowId)) return;
        state.selectedRowsIds.push(rowId);
      },
      UNSELECT_ROW(state, rowId) {
        const index = state.selectedRowsIds.findIndex(id => id == rowId);
        if (index < 0) return;

        state.selectedRowsIds.splice(index, 1);
      },
      TOGGLE_SELECT_ROW(state, rowId) {
        const index = state.selectedRowsIds.findIndex(id => id == rowId);
        if (index < 0) {
          state.selectedRowsIds.push(rowId);
          return;
        }
        state.selectedRowsIds.splice(index, 1);
      },
      SELECT_ALL_ROWS(state) {
        const ids = state.page.pageData.data.map(item => item.id);
        state.selectedRowsIds = ids;
      },
      UNSELECT_ALL_ROWS(state) {
        state.selectedRowsIds = [];
      },
      TOGGLE_SELECT_ALL_ROWS(state) {
        // getters.allRowsAreSelected()
        let allRowsAreSelected = true;
        const ids = _.get(state, 'page.pageData.data', []).map(item => item.id);
        for (let rowId of ids) {
          if (!state.selectedRowsIds.find(id => id == rowId)) {
            allRowsAreSelected = false;
          }
        }
        // end getters.allRowsAreSelected()
        if (allRowsAreSelected) {
          // UNSELECT_ALL_ROWS
          state.selectedRowsIds = [];
        } else {
          // SELECT_ALL_ROWS
          state.selectedRowsIds = ids;
        }
      }
    },
    actions: {
      selectRow(context, rowId) {
        return context.commit('SELECT_ROW', rowId);
      },
      unselectRow(context, rowId) {
        return context.commit('UNSELECT_ROW', rowId);
      },
      toggleSelectRow(context, rowId) {
        return context.commit('TOGGLE_SELECT_ROW', rowId);
      },
      selectAllRows(context) {
        return context.commit('SELECT_ALL_ROWS');
      },
      unselectAllRows(context) {
        return context.commit('UNSELECT_ALL_ROWS');
      },
      toggleSelectAllRows(context) {
        return context.commit('TOGGLE_SELECT_ALL_ROWS');
      },
    },
    modules: {
      page: paginationModule(url, {
        syncFiltersWithRouteParams: true,
        routeParamsPrefix: "table",
        filters: { search: null },
        namespaced: false,
      }),
    },
    namespaced: true
  }
}