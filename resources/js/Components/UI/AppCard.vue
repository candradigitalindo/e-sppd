<script setup>
defineProps({
  title: String,
  padding: { type: Boolean, default: true },
})
</script>

<template>
  <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
    <div v-if="title" class="flex items-center justify-between border-b border-gray-100 px-5 py-4">
      <h3 class="font-semibold text-gray-800">{{ title }}</h3>
      <slot name="actions" />
    </div>
    <div :class="padding ? 'p-5' : ''">
      <slot />
    </div>
  </div>
</template>
