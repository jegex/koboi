import { derived, writable } from 'svelte/store'

/**
 * @typedef {{notifications: any[], notificationsShown: boolean, unreadNotifications: boolean}} NotificationsState
 */

function initialState() {
  return {
    notifications: [],
    notificationsShown: false,
    unreadNotifications: false,
  }
}

export function createNotificationsStore() {
  const store = writable(initialState())
  const { subscribe, update } = store

  const notifications = derived(store, s => s.notifications)
  const notificationsShown = derived(store, s => s.notificationsShown)
  const unreadNotifications = derived(store, s => s.unreadNotifications)

  function toggleNotifications() {
    update(s => {
      const notificationsShown = !s.notificationsShown

      localStorage.setItem('nova.mainMenu.open', notificationsShown)

      return { ...s, notificationsShown }
    })
  }

  async function fetchNotifications() {
    const {
      data: { notifications, unread },
    } = await Nova.request().get(`/nova-api/nova-notifications`)

    update(s => ({
      ...s,
      notifications: notifications,
      unreadNotifications: unread,
    }))
  }

  async function markNotificationAsUnread(id) {
    await Nova.request().post(`/nova-api/nova-notifications/${id}/unread`)
    await fetchNotifications()
  }

  async function markNotificationAsRead(id) {
    await Nova.request().post(`/nova-api/nova-notifications/${id}/read`)
    await fetchNotifications()
  }

  async function deleteNotification(id) {
    await Nova.request().delete(`/nova-api/nova-notifications/${id}`)
    await fetchNotifications()
  }

  async function deleteAllNotifications() {
    await Nova.request().delete(`/nova-api/nova-notifications`)
    await fetchNotifications()
  }

  async function markAllNotificationsAsRead() {
    await Nova.request().post(`/nova-api/nova-notifications/read-all`)
    await fetchNotifications()
  }

  return {
    subscribe,
    notifications,
    notificationsShown,
    unreadNotifications,
    toggleNotifications,
    fetchNotifications,
    markNotificationAsUnread,
    markNotificationAsRead,
    deleteNotification,
    deleteAllNotifications,
    markAllNotificationsAsRead,
  }
}
