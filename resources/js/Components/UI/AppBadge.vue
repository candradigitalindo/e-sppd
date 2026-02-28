<script setup>
defineProps({
  color: { type: String, default: 'gray' },
  size: { type: String, default: 'sm' },
})

const colorClasses = {
  gray: 'bg-gray-100 text-gray-700',
  green: 'bg-green-100 text-green-800',
  blue: 'bg-blue-100 text-blue-800',
  yellow: 'bg-yellow-100 text-yellow-800',
  red: 'bg-red-100 text-red-800',
  purple: 'bg-purple-100 text-purple-800',
  orange: 'bg-orange-100 text-orange-800',
}

const sizeClasses = {
  xs: 'px-1.5 py-0.5 text-xs',
  sm: 'px-2 py-0.5 text-xs',
  md: 'px-2.5 py-1 text-sm',
}
</script>

<template>
  <span
    :class="[
      'inline-flex items-center rounded-full font-medium',
      colorClasses[color] ?? colorClasses.gray,
      sizeClasses[size] ?? sizeClasses.sm,
    ]"
  >
    <slot />
  </span>
</template>
