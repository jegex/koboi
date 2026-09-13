import { derived, get, writable } from 'svelte/store'
import cloneDeep from 'lodash/cloneDeep'
import each from 'lodash/each'
import find from 'lodash/find'
import filter from 'lodash/filter'
import reduce from 'lodash/reduce'
import { escapeUnicode } from '../util/escapeUnicode.js'

/**
 * @typedef {{filters: any[], originalFilters: any[]}} ResourceState
 */

function initialState() {
  return {
    filters: [],
    originalFilters: [],
  }
}

export function createResourceStore() {
  const store = writable(initialState())
  const { subscribe, update, set } = store

  const filters = derived(store, s => s.filters)
  const originalFilters = derived(store, s => s.originalFilters)
  const hasFilters = derived(store, s => Boolean(s.filters.length > 0))

  const currentFilters = derived(store, s =>
    filter(s.filters).map(f => {
      return {
        [f.class]: f.currentValue,
      }
    })
  )

  const currentEncodedFilters = derived(store, s =>
    btoa(escapeUnicode(JSON.stringify(get(currentFilters))))
  )

  const activeFilterCount = derived(store, s => {
    const originalFilters = get(originalFilters)

    return reduce(
      s.filters,
      (result, f) => {
        const originalFilter = find(originalFilters, of => of.class === f.class)
        const originalFilterCloneValue = JSON.stringify(
          originalFilter.currentValue
        )
        const currentFilterCloneValue = JSON.stringify(f.currentValue)
        return currentFilterCloneValue == originalFilterCloneValue
          ? result
          : result + 1
      },
      0
    )
  })

  const filtersAreApplied = derived(
    activeFilterCount,
    count => count > 0
  )

  function getFilter(filterKey) {
    return find(get(store).filters, filter => filter.class == filterKey)
  }

  function getOriginalFilter(filterKey) {
    return find(
      get(store).originalFilters,
      filter => filter.class == filterKey
    )
  }

  function getOptionsForFilter(filterKey) {
    const filter = getFilter(filterKey)
    return filter ? filter.options : []
  }

  function filterOptionValue(filterKey, optionKey) {
    const filter = getFilter(filterKey)

    return find(filter.currentValue, (value, key) => key == optionKey)
  }

  async function fetchFilters(options) {
    let { resourceName, lens = false } = options
    let { viaResource, viaResourceId, viaRelationship, relationshipType } =
      options
    let params = {
      params: {
        viaResource,
        viaResourceId,
        viaRelationship,
        relationshipType,
      },
    }

    const { data } = lens
      ? await Nova.request().get(
          '/nova-api/' + resourceName + '/lens/' + lens + '/filters',
          params
        )
      : await Nova.request().get(
          '/nova-api/' + resourceName + '/filters',
          params
        )

    storeFilters(data)
  }

  async function resetFilterState() {
    each(get(originalFilters), filter => {
      updateFilterState({
        filterClass: filter.class,
        value: filter.currentValue,
      })
    })
  }

  async function initializeCurrentFilterValuesFromQueryString(
    encodedFilters
  ) {
    if (encodedFilters) {
      const initialFilters = JSON.parse(atob(encodedFilters))
      each(initialFilters, filter => {
        if (
          filter.hasOwnProperty('class') &&
          filter.hasOwnProperty('value')
        ) {
          updateFilterState({
            filterClass: filter.class,
            value: filter.value,
          })
        } else {
          for (let key in filter) {
            updateFilterState({
              filterClass: key,
              value: filter[key],
            })
          }
        }
      })
    }
  }

  function updateFilterState({ filterClass, value }) {
    update(s => {
      let filter = find(s.filters, f => f.class == filterClass)

      if (filter !== undefined && filter !== null) {
        filter = { ...filter, currentValue: value }
      }

      return {
        ...s,
        filters: s.filters.map(f =>
          f.class === filterClass ? { ...f, currentValue: value } : f
        ),
      }
    })
  }

  function storeFilters(data) {
    set({
      filters: data,
      originalFilters: cloneDeep(data),
    })
  }

  function clearFilters() {
    set(initialState())
  }

  return {
    subscribe,
    filters,
    originalFilters,
    hasFilters,
    currentFilters,
    currentEncodedFilters,
    activeFilterCount,
    filtersAreApplied,
    getFilter,
    getOriginalFilter,
    getOptionsForFilter,
    filterOptionValue,
    fetchFilters,
    resetFilterState,
    initializeCurrentFilterValuesFromQueryString,
    updateFilterState,
    storeFilters,
    clearFilters,
  }
}
