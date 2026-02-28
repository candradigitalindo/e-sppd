<script setup>
import { Link } from '@inertiajs/vue3'

const props = defineProps({
  links: { type: Array, default: () => [] },
  from: Number,
  to: Number,
  total: Number,
})
</script>

<template>
  <div class="flex flex-col sm:flex-row items-center justify-between gap-3 py-3 text-sm text-gray-600">
    <p v-if="total">
      Menampilkan <strong>{{ from }}</strong>–<strong>{{ to }}</strong>
      dari <strong>{{ total }}</strong> data
    </p>

    <div class="flex items-center gap-1">
      <template v-for="link in links" :key="link.url ?? link.label">
        <Link
          v-if="link.url"
          :href="link.url"
          :class="[
            'px-3 py-1.5 rounded-lg text-xs transition-colors',
            link.active
              ? 'bg-green-600 text-white font-semibold'
              : 'bg-white border border-gray-200 hover:bg-gray-50 text-gray-600',
          ]"
          preserve-scroll
          v-html="link.label"
        />
        <span
          v-else
          class="px-3 py-1.5 rounded-lg text-xs bg-gray-50 text-gray-400 border border-gray-200"
          v-html="link.label"
        />
      </template>
    </div>
  </div>
</template>
