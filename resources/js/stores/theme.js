/* theme.js — createThemeStore: dark-mode state with persistence to
   localStorage + HTML `dark`-class sync (AC#4: mode toggle persists, survives
   refresh and drives the applied theme). Bound to the shell topbar toggle. */
export function createThemeStore() {
  let dark = localStorage.getItem('nova-theme') === 'dark'
  apply(dark)
  const listeners = new Set()
  function apply(active) {
    document.documentElement.classList.toggle('dark', active)
    try { localStorage.setItem('nova-theme', active ? 'dark' : 'light') } catch {}
  }
  return {
    subscribe(fn) { fn(dark); listeners.add(fn); return () => listeners.delete(fn) },
    toggle() { dark = !dark; apply(dark); for (const l of listeners) l(dark) },
  }
}
