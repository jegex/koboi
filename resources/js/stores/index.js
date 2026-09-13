import { createNovaStore } from './nova.js'
import { createNotificationsStore } from './notifications.js'
import { createResourceStore } from './resources.js'

/**
 * @returns {{nova: ReturnType<typeof createNovaStore>, notifications: ReturnType<typeof createNotificationsStore>, resources: Map<string, ReturnType<typeof createResourceStore>>}}
 */
export function createStore() {
  return {
    nova: createNovaStore(),
    notifications: createNotificationsStore(),
    resources: new Map(),
  }
}

/**
 * Register a resource store for the given uri key.
 *
 * @param {ReturnType<typeof createStore>} store
 * @param {string} uriKey
 */
export function registerResourceStore(store, uriKey) {
  if (!store.resources.has(uriKey)) {
    store.resources.set(uriKey, createResourceStore())
  }
}

/**
 * @param {ReturnType<typeof createStore>} store
 * @param {string} uriKey
 * @returns {ReturnType<typeof createResourceStore>|undefined}
 */
export function resourceStore(store, uriKey) {
  return store.resources.get(uriKey)
}
