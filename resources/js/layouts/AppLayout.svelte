<script>
  /* AppLayout.svelte — Nova shell (AC#1): sidebar menu from nova mainMenu,
     topbar with global-search trigger + notifications bell + theme toggle,
     and the active page rendered in the main slot via svelte:component. */
  import { createNovaStore } from '../stores/nova.js'
  import { createNotificationsStore } from '../stores/notifications.js'
  import { createThemeStore } from '../stores/theme.js'
  import Menu from '../ui/Menu.svelte'
  import CommandPalette from '../pages/CommandPalette.svelte'
  import NotificationsPanel from '../ui/NotificationsPanel.svelte'
  import Icon from '../ui/Icon.svelte'
  let { component = null, props = {}, children } = $props()
  const nova = createNovaStore()
  const notifications = createNotificationsStore()
  const theme = createThemeStore()
  let menuItems = $state([])
  let topbarUser = $state({})
  let paletteOpen = $state(false)
  let bellOpen = $state(false)
  nova.subscribe(s => { menuItems = s.mainMenu ?? []; topbarUser = s.currentUser ?? {} })
</script>

<div class="h-screen flex" data-testid="nova-app">
  <aside data-testid="nova-sidebar" class="w-64 border-r dark:border-gray-700 flex flex-col shrink-0">
    <a href="/" class="px-4 py-4 text-sm font-semibold" data-testid="nova-brand">Koboi</a>
    <nav class="flex-1 px-2 space-y-1">
      <Menu items={menuItems} onSelect={(item) => nova.visit(item.path)} />
    </nav>
  </aside>
  <div class="flex-1 flex flex-col min-w-0">
    <header class="h-12 flex items-center gap-3 px-4 border-b dark:border-gray-700" data-testid="nova-topbar">
      <button data-testid="nova-global-search" class="flex-1 text-left text-sm text-gray-400" onclick={() => (paletteOpen = true)}>Search…</button>
      <button data-testid="nova-bell" class="relative" onclick={() => (bellOpen = !bellOpen)}>
        <Icon name="bell" />
        {#if notifications.unreadNotifications}<span data-testid="nova-unread-dot" class="absolute -top-0.5 -right-0.5 h-2 w-2 rounded-full bg-red-500" />{/if}
      </button>
      {#if bellOpen}<NotificationsPanel {notifications} />{/if}
      <button data-testid="nova-theme-toggle" onclick={() => theme.toggle()}>
        <Icon name={theme === /* toggle marker */ null ? 'sun' : 'moon'} />
      </button>
    </header>
    <main class="flex-1 overflow-y-auto p-6" data-testid="nova-content">
      {#if component}<svelte:component this={component} {...props} />{:else if children}{@render children()}{/if}
    </main>
  </div>
  {#if paletteOpen}<CommandPalette onclose={() => (paletteOpen = false)} />{/if}
</div>
