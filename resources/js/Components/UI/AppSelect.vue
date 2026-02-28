<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'

const props = defineProps({
  modelValue: [String, Number],
  label: String,
  options: { type: Array, default: () => [] },
  optionValue: { type: String, default: 'id' },
  optionLabel: { type: String, default: 'nama' },
  placeholder: { type: String, default: '-- Pilih --' },
  error: String,
  required: Boolean,
  disabled: Boolean,
})

const emit = defineEmits(['update:modelValue'])

const open = ref(false)
const search = ref('')
const containerRef = ref(null)
const searchRef = ref(null)

const selectedLabel = computed(() => {
  if (!props.modelValue && props.modelValue !== 0) return null
  const found = props.options.find(o => String(o[props.optionValue]) === String(props.modelValue))
  return found ? found[props.optionLabel] : null
})

const filtered = computed(() => {
  if (!search.value) return props.options
  const q = search.value.toLowerCase()
  return props.options.filter(o =>
    String(o[props.optionLabel] ?? '').toLowerCase().includes(q)
  )
})

function toggle() {
  if (props.disabled) return
  open.value = !open.value
  if (open.value) {
    search.value = ''
    setTimeout(() => searchRef.value?.focus(), 50)
  }
}

function select(opt) {
  emit('update:modelValue', opt[props.optionValue])
  open.value = false
  search.value = ''
}

function clear() {
  emit('update:modelValue', '')
  open.value = false
  search.value = ''
}

function onOutsideClick(e) {
  if (containerRef.value && !containerRef.value.contains(e.target)) {
    open.value = false
  }
}

function onKeydown(e) {
  if (e.key === 'Escape') open.value = false
}

onMounted(() => {
  document.addEventListener('mousedown', onOutsideClick)
  document.addEventListener('keydown', onKeydown)
})
onUnmounted(() => {
  document.removeEventListener('mousedown', onOutsideClick)
  document.removeEventListener('keydown', onKeydown)
})
</script>

<template>
  <div ref="containerRef" class="relative space-y-1.5">
    <label v-if="label" class="block text-sm font-medium text-gray-700">
      {{ label }}
      <span v-if="required" class="ml-0.5 text-red-500">*</span>
    </label>

    <!-- Trigger button -->
    <button
      type="button"
      :disabled="disabled"
      :class="[
        'flex w-full items-center justify-between rounded-lg border px-3 py-2 text-sm shadow-sm transition text-left',
        'focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500',
        'disabled:bg-gray-100 disabled:cursor-not-allowed',
        error ? 'border-red-400' : 'border-gray-300 bg-white',
      ]"
      @click="toggle"
    >
      <span :class="selectedLabel ? 'text-gray-900' : 'text-gray-400'">
        {{ selectedLabel ?? placeholder }}
      </span>
      <span class="ml-2 flex shrink-0 items-center gap-1">
        <!-- Clear button -->
        <span
          v-if="selectedLabel && !disabled"
          class="flex h-4 w-4 items-center justify-center rounded-full text-gray-400 hover:bg-gray-200 hover:text-gray-600"
          @click.stop="clear"
        >
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-3 w-3">
            <path d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z" />
          </svg>
        </span>
        <!-- Chevron -->
        <svg
          xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
          :class="['h-4 w-4 text-gray-400 transition-transform', open ? 'rotate-180' : '']"
        >
          <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
        </svg>
      </span>
    </button>

    <!-- Dropdown -->
    <div
      v-if="open"
      class="absolute z-50 mt-1 w-full rounded-lg border border-gray-200 bg-white shadow-lg"
    >
      <!-- Search input -->
      <div class="border-b border-gray-100 p-2">
        <div class="flex items-center gap-2 rounded-md border border-gray-200 bg-gray-50 px-2 py-1.5">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4 shrink-0 text-gray-400">
            <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 1 0 0 11 5.5 5.5 0 0 0 0-11ZM2 9a7 7 0 1 1 12.452 4.391l3.328 3.329a.75.75 0 1 1-1.06 1.06l-3.329-3.328A7 7 0 0 1 2 9Z" clip-rule="evenodd" />
          </svg>
          <input
            ref="searchRef"
            v-model="search"
            type="text"
            placeholder="Cari..."
            class="w-full bg-transparent text-sm text-gray-700 placeholder-gray-400 focus:outline-none"
          />
        </div>
      </div>

      <!-- Options list -->
      <ul class="max-h-52 overflow-y-auto py-1">
        <!-- Placeholder / clear option -->
        <li
          class="cursor-pointer px-3 py-2 text-sm text-gray-400 hover:bg-gray-50"
          @click="clear"
        >
          {{ placeholder }}
        </li>
        <li
          v-for="opt in filtered"
          :key="opt[optionValue]"
          :class="[
            'cursor-pointer px-3 py-2 text-sm transition',
            String(opt[optionValue]) === String(modelValue)
              ? 'bg-green-50 font-medium text-green-700'
              : 'text-gray-800 hover:bg-gray-50',
          ]"
          @click="select(opt)"
        >
          {{ opt[optionLabel] }}
        </li>
        <li v-if="filtered.length === 0" class="px-3 py-2 text-sm text-gray-400">
          Tidak ada hasil
        </li>
      </ul>
    </div>

    <p v-if="error" class="text-xs text-red-600">{{ error }}</p>
  </div>
</template>
