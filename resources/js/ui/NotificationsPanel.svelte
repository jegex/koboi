<script>
  /* NotificationsPanel.svelte — AC#3 notifications UI bound to the real
     notifications store contract: notifications list, mark read, mark
     unread, delete. `notifications` is the array AppLayout pulls from the
     store; actions call the store mutations directly. */
  import { createNotificationsStore } from '../stores/notifications.js'
  import Icon from './Icon.svelte'
  let { notifications = [] } = $props()
  let notificationsStore = createNotificationsStore()
</script>

<div
  class="absolute right-0 mt-1 w-80 rounded-lg border dark:border-gray-700 bg-white dark:bg-gray-800 shadow-lg z-50"
  data-testid="nova-notifications-panel"
>
  <div class="flex items-center justify-between px-3 py-2 border-b dark:border-gray-700">
    <h3 class="text-sm font-medium">Notifications</h3>
    <button data-testid="nova-notifications-close" onclick={() => (notificationsStore.toggleNotifications())}>
      <Icon name="x-mark" class="w-4 h-4 text-gray-400" />
    </button>
  </div>
  {#if notifications.length === 0}
    <p class="px-3 py-4 text-sm text-gray-400" data-testid="nova-notifications-empty">You're all caught up.</p>
  {:else}
    <ul class="max-h-64 overflow-y-auto">
      {#each notifications as note}
        <li class="flex items-center gap-2 px-3 py-2 text-sm border-b dark:border-gray-700 last:border-b-0" data-testid="nova-notification">
          <Icon name="bell" class="w-4 h-4 text-gray-400 shrink-0" />
          <span class="flex-1 text-gray-700 dark:text-gray-200">{note.message}</span>
          {#if note.unread}
            <button data-testid="nova-notification-mark-read" onclick={() => notificationsStore.markNotificationAsRead(note.id)}>
              <Icon name="check" class="w-4 h-4 text-emerald-500" />
            </button>
          {/if}
          <button data-testid="nova-notification-delete" onclick={() => notificationsStore.deleteNotification(note.id)}>
            <Icon name="x-mark" class="w-4 h-4 text-gray-400" />
          </button>
        </li>
      {/each}
    </ul>
  {/if}
</div>
