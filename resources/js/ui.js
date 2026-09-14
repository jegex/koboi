import Badge from './ui/Badge.svelte'
import Button from './ui/Button.svelte'
import Checkbox from './ui/Checkbox.svelte'
import Icon from './ui/Icon.svelte'
import Loader from './ui/Loader.svelte'

export function cn(...parts) {
  return parts.filter(Boolean).join(' ')
}

export function createNovaUi(config = {}) {
  return {
    config,
    cn,
    Badge,
    Button,
    Checkbox,
    Icon,
    Loader,
  }
}

export { Badge, Button, Checkbox, Icon, Loader }

export default {
  cn,
  createNovaUi,
  Badge,
  Button,
  Checkbox,
  Icon,
  Loader,
}
