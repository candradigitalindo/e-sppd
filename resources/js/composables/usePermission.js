import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

export function usePermission() {
  const page = usePage()
  const user = computed(() => page.props.auth?.user)

  function can(permission) {
    return user.value?.permissions?.includes(permission) ?? false
  }

  function hasRole(role) {
    return user.value?.role?.slug === role
  }

  const isAdmin = computed(() => hasRole('admin') || hasRole('super_admin'))

  return { can, hasRole, isAdmin, user }
}
