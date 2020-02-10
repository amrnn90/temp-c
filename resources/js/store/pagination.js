import router from '@/router';

const defaultOptions = {
  filters: {},
  syncFiltersWithRouteParams: false,
  routeParamsPrefix: null,
  namespaced: true
};

export default (url, opts = {}) => {
  const options = { ...defaultOptions, ...opts };
  const filters = { ...options.filters, page: null };

  let initialFilters = filters;
  if (options.syncFiltersWithRouteParams) {

    initialFilters = getInitialFiltersFromRouteParams(filters, options.routeParamsPrefix);

    syncFiltersWithRouteParams(initialFilters, options.routeParamsPrefix);
  }

  return {
    state() {
      return {
        pageData: null,
        isLoading: false,
        error: null,
        filters: initialFilters,
        oldFilters: {},
        isNewPage: null,
      }
    },
    getters: {
      totalPages(state) {
        return Math.ceil(
          _.get(state, "pageData.total", 0) /
          _.get(state, "pageData.per_page", 10)
        );
      },
      currentPage(state) {
        return _.get(state, 'pageData.current_page', 1);
      },
      lastPage(state, getters) {
        return getters.totalPages;
      },
      isFirstPage(state, getters) {
        return getters.currentPage == 1;
      },
      isLastPage(state, getters) {
        return getters.currentPage == getters.lastPage;
      },
      isLoading(state) {
        return state.isLoading;
      },
      error(state) {
        return state.error;
      },
      pageData(state) {
        return _.get(state, 'pageData');
      },
      items(state) {
        return _.get(state, 'pageData.data', []);
      },
      filters(state) {
        return state.filters;
      },
      nonEmptyFilters(state, getters) {
        return _.omitBy(state.filters, _.isEmpty);
      },
      filtersPage(state, getters) {
        return getters.filters.page;
      },

      /* watch this to know if the last loaded page has different filters from previous one */
      isNewPage(state, getters) {
        return state.isNewPage;
      }
    },
    mutations: {
      LOAD_PAGE_INIT(state) {
        state.isLoading = true;
        state.isNewPage = null;
        state.error = null;
      },
      LOAD_PAGE_SUCCESS(state, pageData) {
        state.pageData = { ...pageData };
        state.isLoading = false;
        state.error = null;

        if (_.isEqual(state.filters, state.oldFilters)) {
          state.isNewPage = false;
        } else {
          state.isNewPage = true;
        }

        state.oldFilters = { ...state.filters };
      },
      LOAD_PAGE_ERROR(state, error) {
        state.isLoading = false;
        state.error = error;
      },
      UPDATE_FILTERS(state, newFilters) {
        state.oldFilters = { ...state.filters };
        state.filters = { ...newFilters };
      }
    },
    actions: {
      load({ commit, dispatch, state, getters }) {
        const page = parseInt(getters.filtersPage);
        if (page < 1 || isNaN(page)) {
          return dispatch('updateFilters', { page: '1' });
        }
        commit('LOAD_PAGE_INIT');
        return axios
          .get(url, { params: { ...getters.nonEmptyFilters } })
          .then(({ data }) => {
            commit('LOAD_PAGE_SUCCESS', data);

            if (getters.currentPage > getters.lastPage) {
              return dispatch('updateFilters', { page: getters.lastPage.toString() });
            }
          })
          .catch(error => {
            commit('LOAD_PAGE_ERROR', error);
          });
      },
      refresh({ dispatch, getters }) {
        return dispatch('load');
      },
      updateFilters({ commit, dispatch, state }, filters) {
        const newFilters = {
          ...state.filters,
          ...filters,
          page: filters.page || '1',
        };

        const oldFilters = state.filters;

        if (!_.isEqual(newFilters, oldFilters)) {
          commit('UPDATE_FILTERS', newFilters);

          if (options.syncFiltersWithRouteParams) {
            syncFiltersWithRouteParams(newFilters, options.routeParamsPrefix);
          }
          return dispatch('load');
        }
      },
      clearFilters({ dispatch }) {
        return dispatch('updateFilters', {});
      }
    },
    namespaced: options.namespaced
    ,
  }
}



function applyPrefix(filters, prefix) {
  prefix = prefix ? `${prefix}_` : '';
  const result = {};

  Object.keys(filters).forEach(key => {
    result[prefix + key] = filters[key];
  });

  return result;
}

function removePrefix(filters, prefix) {
  prefix = prefix ? `${prefix}_` : '';
  const result = {};

  Object.keys(filters).filter(key => key.startsWith(prefix)).forEach(key => {
    result[key.split(prefix).slice(1).join('')] = filters[key];
  });

  return result;
}

function getFiltersFromQueryParams(filters, prefix) {
  const allQueryParams = router.currentRoute.query;
  const prefixedFilters = applyPrefix(filters, prefix);

  return removePrefix(_.pick(allQueryParams, Object.keys(prefixedFilters)), prefix);
}

function getInitialFiltersFromRouteParams(filters, prefix) {
  const unprefixedQueryParams = getFiltersFromQueryParams(filters, prefix);
  const initialFilters = { ...filters, ...unprefixedQueryParams };

  return initialFilters;
}

function syncFiltersWithRouteParams(filters, prefix) {
  const allQueryParams = router.currentRoute.query;
  const prefixedFilters = applyPrefix(filters, prefix);
  const newQueryParams = _.omitBy({ ...allQueryParams, ...prefixedFilters }, _.isEmpty);

  if (!_.isEqual(newQueryParams, allQueryParams)) {

    router.replace({
      ...router.currenRoute,
      query: newQueryParams
    });

  }
}