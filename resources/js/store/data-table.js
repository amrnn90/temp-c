import paginationModule from '@/store/pagination';

export default (url) => {
  return {
    state: {
      selectedRows: [],
    },
    getters: {
      selectedRows(state) {
        return state.selectedRows;
      },
      isSelectedRow: (state) => (rowId) => {
        return !!state.selectedRows.find(id => id == rowId);
      },
    },
    mutations: {
      SELECT_ROW(state, rowId) {
        if (!!state.selectedRows.find(id => id == rowId)) return;
        state.selectedRows.push(rowId);
      },
      UNSELECT_ROW(state, rowId) {
        const index = state.selectedRows.findIndex(id => id == rowId);
        if (index < 0) return;

        state.selectedRows.splice(index, 1);
      },
      TOGGLE_ROW(state, rowId) {
        const index = state.selectedRows.findIndex(id => id == rowId);
        if (index < 0) {
          state.selectedRows.push(rowId);
          return;
        }
        state.selectedRows.splice(index, 1);
      },
    },
    actions: {
      selectRow(context, rowId) {
        return context.commit('SELECT_ROW', rowId)
      },
      unselectRow(context, rowId) {
        return context.commit('UNSELECT_ROW', rowId)
      },
      toggleRow(context, rowId) {
        return context.commit('TOGGLE_ROW', rowId)
      },
    },
    modules: {
      page: paginationModule(url, {
        syncFiltersWithRouteParams: true,
        routeParamsPrefix: "table",
        filters: {},
        namespaced: false,
      }),
    },
    namespaced: true
  }
}