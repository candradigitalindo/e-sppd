<script setup>
defineProps({
  title: String,
  description: String,
  breadcrumbs: { type: Array, default: () => [] },
})
</script>

<template>
  <div class="mb-6">
    <!-- Breadcrumbs -->
    <nav v-if="breadcrumbs.length" class="mb-2 flex items-center gap-1 text-xs text-gray-500">
      <template v-for="(crumb, i) in breadcrumbs" :key="crumb.label">
        <span v-if="i > 0" class="text-gray-400">/</span>
        <a
          v-if="crumb.href"
          :href="crumb.href"
          class="hover:text-green-600 transition-colors"
        >{{ crumb.label }}</a>
        <span v-else class="text-gray-700 font-medium">{{ crumb.label }}</span>
      </template>
    </nav>

    <!-- Title row -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
      <div>
        <h1 class="text-xl font-bold text-gray-900">{{ title }}</h1>
        <p v-if="description" class="mt-0.5 text-sm text-gray-500">{{ description }}</p>
      </div>
      <div class="flex items-center gap-2 shrink-0">
        <slot />
      </div>
    </div>
  </div>
</template>
