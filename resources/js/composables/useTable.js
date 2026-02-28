import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'

export function useTable(initialFilters = {}) {
  const filters = ref({ ...initialFilters })
  const loading = ref(false)

  function apply(newFilters = {}) {
    Object.assign(filters.value, newFilters)
    router.get(
      window.location.pathname,
      Object.fromEntries(
        Object.entries(filters.value).filter(([, v]) => v !== '' && v !== null && v !== undefined)
      ),
      {
        preserveState: true,
        preserveScroll: true,
        replace: true,
        onStart: () => (loading.value = true),
        onFinish: () => (loading.value = false),
      }
    )
  }

  function reset() {
    filters.value = { ...initialFilters }
    apply()
  }

  return { filters, loading, apply, reset }
}
